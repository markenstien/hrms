<?php 
	/**
	 * model to calculate
	 * timelogs and save to timesheet
	 * */
	class TimelogMetaModel extends Model
	{
		public $table = 'time_logs';


		private static $WORK_HOURS_TOTAL_TODAY = 'WORK_HOURS_TOTAL_TODAY';
		public static $CLOCKED_IN = 'logged in';
		public static $CLOCKED_OUT = 'logged out';

		/**
		 * maximum straight working hours
		 * staffs must logged-out every 4 hours otherwise
		 * their timelog will be cancelled
		 * this is in minutes
		 */
		private static $MAX_WORK_HOURS_IN_MINS = 270;

		//fill n runtime
		private $action , $user , $workData;

		private $userMeta,$userId;
		private $overTime = FALSE;

		public function __construct()
		{
			parent::__construct();

			$this->user_meta_model = model('UserMetaModel');
			$this->user_model = model('UserModel');
			$this->timesheet = model('TimesheetModel');
		}

		/**
		 * log read user if 
		 * log-in or log-out
		 * */
		public function log($userId)
		{
			$this->userMeta = $this->user_meta_model->getByUserid($userId);
			$this->user = $this->user_model->get($userId);
			$this->userId = $userId;
			/*
			*check if user is currently clocked in
			*/
			$this->last = $this->getLastLog($userId);

			if (!empty($this->last) && is_null($this->last->clock_out_time)) {
				/*
				*currently logged in then do log-out
				*/
				$this->action = self::$CLOCKED_OUT;
				$isOkay = $this->clockOut($userId);

				if($isOkay) {
					$this->setAction('CLOCKOUT');
				}
				return $isOkay;
			} else {
				$this->action = self::$CLOCKED_IN;
				$isOkay = $this->clockIn($userId);

				if($isOkay) {
					$this->setAction('CLOCKIN');
				}
				return $isOkay;
			}
		}

		public function getLastLog($userId)
		{
			return parent::single([
				'user_id' => $userId
			] , '*' , 'id desc');
		}

		public function logType($log)
		{	
			if(!$log)
				return self::$CLOCKED_OUT;
			if(is_null($log->clock_out_time))
				return self::$CLOCKED_IN;
			return self::$CLOCKED_OUT;
		}

		private function clockIn($userId)
		{
			$dateTime = nowMilitary();
            $schedule = mGetSchedule($this->userMeta->user_id);
			
			//check if schedule is today
			if ($schedule) {
				if ($schedule->is_off) {
                    $this->addError(" Today is your day off");
                    return false;
                }
			}

			//check login

			$isMaxedWorkHours = $this->isMaxedWorkHours($this->userMeta);
			if($isMaxedWorkHours) {
				$this->addError("You already maxed your work hours");
				return false;
			}

			$lastPunchTime = $this->last ? $this->last->clock_out_time : $dateTime;
			$userMaxWorkHours = $this->user_meta_model->getMaxWorkHours($userId);
			$userWorkHoursToday = $this->getWorkHoursByUser(self::$WORK_HOURS_TOTAL_TODAY , $userId);
			//how to check if overtime
            $workHoursRendered = timeDifferenceInMinutes($lastPunchTime, $dateTime);

			$workData = $this->workTimeData($workHoursRendered , 
                $userMaxWorkHours, 
                $userWorkHoursToday);
            /*
            *check if schedule time on in and out
            */
            $response = $this->isAllowedToClockIn($dateTime , $schedule , $workData);

            if (!$response) {
                $this->addError("Invalid Time-in you reached your max work hours");
                return false;
            }
            $this->addMessage("User clocked in successful");
			return parent::store([
				'user_id' => $userId,
				'clock_in_time' => $dateTime,
				'is_ot' => $this->overTime
			]);
		}

		private function clockOut($userId)
		{
			$remarks = [];
			// $this->last;
			$dateToday = nowMilitary();
			$lastPunchTime = $this->last->clock_in_time;
			/**
			 * special logout
			 * for daily basis worker
			 */
			$userData = $this->user_model->getMeta($userId);
			$userDataMeta = $userData->userMeta;

			//dailly wage workder
			if (!is_null($userDataMeta->weekly_max_earning) && ($userDataMeta->weekly_max_earning > 0)) 
			{
				//check for double login
				$alreadyLoggedIn = parent::dbgetDesc('id', parent::convertWhere([
					'user_id' => $userId,
					'date(clock_in_time)' => date('Y-m-d', strtotime($dateToday)) 
				]));

				if ($alreadyLoggedIn) {
					$isUpdated = parent::update([
						'clock_out_time' => $dateToday,
						'approval_status' => 'approved',
						'total_time_duration' => 0
					] , $this->last->id);
					$this->retVal = [
						'message' => 'You already logged in today. please login tomorrow'
					];
					$this->addMessage('You already logged in today. please login tomorrow');
					return true;
				}

				$numberOfWorkDays = 0;

				foreach ($userData->schedule as $key => $row) {
					if(!$row->is_off)
						$numberOfWorkDays++;
				}
				
				$validWorkHours = $userDataMeta->work_hours * 60;
				$amount = $userDataMeta->weekly_max_earning / $numberOfWorkDays;
				$isUpdated = parent::update([
					'clock_out_time' => $dateToday,
					'approval_status' => 'approved',
					'total_time_duration' => $userDataMeta->work_hours * 60
				] , $this->last->id);

				if ($isUpdated)  {
					$timeSheet = [
						'user_id' =>  $userId,
						'time_in' =>  $lastPunchTime,
						'time_out' => $dateToday,
						'duration' => $validWorkHours,
						'amount'   => $amount ,
						'remarks'  => implode(',', $remarks),
						'status'   => 'pending' ,
						'is_ot'    => $this->last->is_ot,
						'flushed_hours' => 0
					];
		
					$timeSheetMeta = [
						'rate'     => $this->userMeta->rate_per_day,
						'clock_in_id' => $this->last->id,
						'clock_out_id' => $this->last->id
					];
					//check total hours
					$timeSheet = $this->timesheet->save($timeSheet, $timeSheetMeta);
					//update clockout
					$this->retVal = [
						'timeSheetId' => $this->timesheet->id,
						'timesheetMetaId' => $this->timesheet->metaId
					];
				}

			} else {
				$isCancelled = false;
				//work hours rendered when clock out is pressed
				$onGoingWorkHours = timeDifferenceInMinutes($lastPunchTime, $dateToday);
				$userMaxWorkHours = $this->user_meta_model->getMaxWorkHours($userId);
				$userMaxWorkHours = ($userMaxWorkHours * 60) + 15; //convert hours in minutes add 30minutes grace time
				$totalWorkedHours = $this->getTotalWorkedHours([
					'userId' => $userId,
					'from' => 'today'
				]);

				$validWorkHours = $onGoingWorkHours;

				$payableWork = [
					'duration' => 0,
					'amount' => 0
				];

				$excessWork = [
					'duration' => 0,
					'amount' => 0
				];

				if(($totalWorkedHours + $onGoingWorkHours) > $userMaxWorkHours) {
					$isCancelled = true;
					$payableWorkDuration = $userMaxWorkHours - $totalWorkedHours;
					
					/**no payable work */
					if($payableWorkDuration < 0) {
						return false;
					}

					$payableWorkAmount = $this->timesheet->computeSalaryWithDuration($this->userMeta->rate_per_hour, $payableWorkDuration);
					$payableWork = [
						'duration' => $payableWorkDuration,
						'amount'   => $payableWorkAmount
					];

					$excessWorkDuration = ($totalWorkedHours + $onGoingWorkHours) - $userMaxWorkHours;
					$excessWorkAmount = $this->timesheet->computeSalaryWithDuration($this->userMeta->rate_per_hour, $excessWorkDuration);

					if(!$excessWorkDuration) {
						return false;
					}
					$excessWork = [
						'duration' => $excessWorkDuration,
						'amount' => $excessWorkAmount
					];

					$workHoursText = minutesToHours($userMaxWorkHours);
					$exceedWorkHours = minutesToHours($excessWorkDuration);

					$this->addError(
						"Exceded Maximum work hours of {$workHoursText},
							Exceed Hours  : {$exceedWorkHours},
							Exceed Amount : {$excessWorkAmount} "
					);
				} else {
					$payableWork = [
						'amount' => $this->timesheet->computeSalaryWithDuration($this->userMeta->rate_per_hour, $validWorkHours),
						'duration' => $validWorkHours
					];
				}

				if($isCancelled) {
					//create new entry for cancelled data
					if($excessWork['amount']) {
						$this->timesheet->store([
							'user_id' => $userId,
							'time_in' => $lastPunchTime,
							'time_out' => $dateToday,
							'duration' => $excessWork['duration'],
							'amount'   => $excessWork['amount'],
							'remarks'  => $this->getErrorString(),
							'status' => 'cancelled',
							'flushed_hours' => $excessWork['duration']
						]);
					}
				}

				/**
				 * UPDATE LOG DATA
				 */
				$isUpdated = parent::update([
					'clock_out_time' => $dateToday,
					'approval_status' => 'approved',
					'total_time_duration' => $payableWork['duration']
				] , $this->last->id);

				if ($isUpdated) 
				{
					$timeSheet = [
						'user_id' =>  $userId,
						'time_in' =>  $lastPunchTime,
						'time_out' => $dateToday,
						'duration' => $payableWork['duration'],
						'amount'   => $payableWork['amount'] ,
						'status'   => 'approved',
						'is_ot'    => $this->last->is_ot
					];
		
					$timeSheetMeta = [
						'rate'     => $this->userMeta->rate_per_day,
						'clock_in_id' => $this->last->id,
						'clock_out_id' => $this->last->id
					];
					//check total hours
					$timeSheet = $this->timesheet->save($timeSheet, $timeSheetMeta);
					//update clockout
					$this->retVal = [
						'timeSheetId' => $this->timesheet->id,
						'timesheetMetaId' => $this->timesheet->metaId
					];
					
				} else{
					$this->addMessage('Unable to clock-out');
					$this->retVal = [
						'message' => 'Unable to clock-out'
					];
					return false;
				}
			}
			$this->addMessage("User clocked out successful");
			return true;
		}

		/*
        *Workhours today is already in minutes
        *maxwork hours is in hour format
        *ALl datas are returned in minutes
        *workHoursRendered : in minutes
        */
        private function workTimeData($workHoursRendered, $maxWorkHours, $workHoursToday)
        {
            //in minutes
            $workHoursMax = hoursToMinutes($maxWorkHours);
            //in minutes
            $allowedWorkHours = $workHoursMax - $workHoursToday;
            //initialy set valid work hours
            $validWorkHours = $workHoursRendered;
            $flushedWorkHours = 0;

            $flushedMsg = '';

            if($workHoursRendered >= $allowedWorkHours)
            {
                //if the work hours surpass the valid work hours
                //then set the valid work hours
                $validWorkHours = $allowedWorkHours;
                //get the flushed workhours or the thank you! time rendered
                $flushedWorkHours = $workHoursRendered - $validWorkHours;
                if($flushedWorkHours != 0)
                    $flushedMsg = 'flushed work hours '.minutesToHours($flushedWorkHours).' due to your max work hours is only '.minutesToHours($maxWorkHours);
            }

            $returnData = [
                'maxWorkHours' => $workHoursMax,
                'workHoursToday' => $workHoursToday,
                'validWorkHours' => $validWorkHours,
                'flushed' => [
                    'hours' => $flushedWorkHours,
                    'msg'   => $flushedMsg
                ]
            ];
			$this->workData = $returnData;
            return $returnData;
        }


        public function getWorkHours($type = 'default' , $userId = null)
        {
        	$dateToday = nowMilitary();
        	$retVal = null;
        	//default query
        	$sql = "SELECT tlog.user_id as user_id , sum(total_time_duration) as work_hours ,
        		user.username as username, concat(user.firstname , ' ' ,user.lastname) as fullname
        		FROM {$this->table} as tlog
        		LEFT JOIN users as user 
        		ON user.id = tlog.user_id";

        	switch($type)
        	{
        		case self::$WORK_HOURS_TOTAL_TODAY:
        			$sql .= " WHERE date(tlog.created_at) = date('{$dateToday}')";
        		break;
        	}

        	if (!isEqual($type,'default') && !is_null($userId)) 
        		$sql .= " AND tlog.user_id = '{$userId}' ";

        	$sql .= " GROUP BY tlog.user_id ORDER BY user.firstname asc";



        	$this->db->query($sql);
        	return $this->db->resultSet();
        }

        public function getWorkHoursByUser($userId , $type = 'default')
        {
        	$workHours = $this->getWorkHours($type , $userId);

        	if(!empty($workHours))
        		return $this->workHours[0]->work_hours ?? 0;

        	return false;
        }


		
		private function isAllowedToClockIn($punchTime , $schedule , $workTimeData)
        {
            /**
            *Do not validate if no schedule
            */
            if(!$schedule)
                return true;

            //check if early time-in
            $timeInValidation = timeDifference($schedule->time_in , $punchTime);
            /*
            *If negative that means the punchtime is too early 
            *for the users schedule
            */

            //check over time time-in
            $timeInValidation = timeDifference($schedule->time_out ,$punchTime);
            /*
            *If positive then schedule for the day is already over
            */

            if(hoursToMinutes($timeInValidation) > 5)
            {
                if(intval($workTimeData['maxWorkHours']) <= intval($workTimeData['workHoursToday'])){
                    $this->addError("You work hours has been maxed out");
                    return false;
                }
				
                $this->overTime = TRUE;
                return true;
            }

            return true;
        }
		public function getClockedInUsers($params = [])
		{
			$where = null;
			if (isset($params['where']) && !empty($params['where']['branch_id'])) {
				$where = ' WHERE '. parent::convertWhere($params['where']);
				$where .= " AND ";
			} else {
				$where = " WHERE ";
			}
			
			$where .= " clock_out_time IS NULL
			AND user.is_deleted = false ";
			$dateNow = today();

			$this->db->query(
				"SELECT tklog.* , user.username as username , 
					concat(user.firstname , ' ' , user.lastname) as fullname,
					max_work_hours,work_hours,branch_id, branch.branch as branch_name,
					ifnull(total_work_hours.total_duration ,0) as total_duration

					FROM {$this->table} as tklog 
					
					LEFT JOIN users as user 
					ON user.id = tklog.user_id

					LEFT JOIN user_meta
					ON user.id = user_meta.user_id

					LEFT JOIN branches as branch
					ON branch.id = user.branch_id

					LEFT JOIN (
						SELECT sum(duration) as total_duration,
						user_id
						FROM hr_time_sheets
						WHERE date(time_in) = '{$dateNow}'
						GROUP BY user_id
					) as total_work_hours
					ON total_work_hours.user_id = user.id
					{$where}
					ORDER BY branch.branch asc, user.firstname asc"
			);

			return $this->db->resultSet();
		}

		public function getClockedOutUsers($params = [])
		{
			$users = $this->getClockedInUsers($params);

			$branch_id = null;
			
			if(isset($params['where'])) {
				if(isset($params['where']['branch_id']) && !empty($params['where']['branch_id'])) {
					$branch_id = " AND branch_id = '{$params['where']['branch_id']}'";
				}
			}
			
			if (empty($users))  {
				$this->db->query(
					"SELECT user.*,concat(firstname , ' ' ,lastname) as fullname,
						branch.branch as branch_name 
						FROM {$this->user_model->table} as user
						LEFT JOIN branches as branch
						ON branch.id = user.branch_id
						WHERE is_deleted = false
						{$branch_id}
						ORDER BY branch.branch asc, firstname asc"
				);
			} else {
				$logged_in_user_id = [];

				foreach($users as $key => $row) {
					$logged_in_user_id[] = $row->user_id;
				}

				$this->db->query(
					"SELECT user.*,concat(firstname , ' ' ,lastname) as fullname,
						branch.branch as branch_name
						FROM {$this->user_model->table} as user
						LEFT JOIN branches as branch
						ON branch.id = user.branch_id
						WHERE user.id not in('".implode("','" , $logged_in_user_id)."')
						{$branch_id}
						AND is_deleted = false
						ORDER BY branch.branch asc, firstname asc"
				);
			}

			return $this->db->resultSet();
		}

		//supporting functions
		public function getValidWorkHours()
		{
			if (isset($this->workData)) {
				return $this->workData['validWorkHours'];
			}else{
				return 0;
			}
		}

		public function getUser()
		{
			return $this->user;
		}

		public function getAction()
		{
			return $this->action;
		}

		public function setAction($action) {
			$this->action = $action;
		}

		/**
		 * total workhours should be in
		 * minutes format
		 */
		private function validateTimesheet($totalWorkHours , $timesheetId)
		{
			if ($totalWorkHours >= self::$MAX_WORK_HOURS_IN_MINS)
			{
				$this->timesheet->update([
					'status' => $this->timesheet::$STATUS_CANCEL,
					'remarks' => " You're account must logout aleast every 4hours,
					hours flushed. {$totalWorkHours}"
				] , $timesheetId);

				return false;
			}

			return true;
		}


		private function checkMinWorkHours($totalWorkHoursInMinutes)
		{
			if ($totalWorkHoursInMinutes < 2) {
				$this->addError("Try Again Later,Please wait 2mins");
				return false;
			}
			return true;
		}
		/**
		 * if incomming workhour is not empty
		 * add it to current total work duration
		 */
		public function isMaxedWorkHours($userMeta, $incommingWorkHour = null) {
			//calculate current login (until max hours only).
			$today = today();
			//get duration of logins today.
			//logout then invalidate if max hours grace time 30mins
			$timesheetModel = model('TimesheetModel');
			$logs = $timesheetModel->getAll([
				'date(tklog.time_in)' => $today,
				'tklog.user_id' => $userMeta->user_id
			]);

			$duration = 0;
			if($logs) {
				$duration = 0;
				foreach($logs as $key => $row) {
					$duration += $row->duration;
				}
			}

			if (!is_null($incommingWorkHour)) {
				//in minutes
				$duration += $incommingWorkHour;
			}

			//convert max hour from hour to minutes
			if ($duration >= ($userMeta->max_work_hours * 60)) {
				//if duration is already bigger than total max hour then is maxed = true
				return true;
			}

			return false;
		}

		/**
		 * get ongoing working hours
		 * calculate login time to logout time,
		 * calcualte only not save into db
		 */
		public function getOnGoingWorkHours($userId) {
			$lastLog = $this->getLastLog($userId);
			$dateToday = nowMilitary();
			$lastPunchTime = $lastLog->clock_in_time;

			return timeDifferenceInMinutes(strtotime($lastPunchTime), strtotime($dateToday));
		}
		/**
		 *get saved data from
		 *timesheets
		 *valid params from - to , userId
		 */
		public function getTotalWorkedHours($params = []) {
			$where = null;
			$dateToday = nowMilitary();
			
			if(!empty($params)) {
				$whereArray = [];
				if($params['from'] == 'today') {
					$whereArray['date(hr_time_sheets.time_in)'] = date('Y-m-d',strtotime($dateToday));
				}

				if(isset($params['userId'])) {
					$whereArray['user_id'] = $params['userId'];
				}
			}
			$whereArray['status'] = 'approved';

			$where = " WHERE ". parent::convertWhere($whereArray);
			
			$this->db->query(
				"SELECT sum(duration) as duration FROM hr_time_sheets
					{$where}
					GROUP BY user_id"
			);
			
			return $this->db->single()->duration ?? 0;
		}
	}