<?php   

    /*
    *old  time log
    *but do not delete some services 
    *are relying to this model
    */

    class TimelogModel extends Model

    {

        public $table = 'hr_time_logs';

        public $logs = []; //can be posted on timesheets or time logs

        public $overTime = false;

        public $retVal;


        public function __construct()

        {

            parent::__construct();



            $this->timesheet = model('TimesheetModel');

            $this->timesheetMeta = model('TimesheetMetaModel');

            $this->user  = model('UserModel');

            $this->automaticLogout = model('AutomaticLogoutSettingModel');

        }



        public function clockIn($userId)
        {
            $dateTime = nowMilitary();

            $user = $this->user->getMeta($userId);

            $userMeta = $user->userMeta;

            $schedule = mGetSchedule($userId);

            $lastRow = $this->lastRow($userId);

            $lastPunchTime = $lastRow->punch_time;


            if($schedule)
                if($schedule->is_off){
                    $this->addError(" Today is your day off");
                    return false;
                }

            $WORK_HOURS_RENDERED = timeDifferenceInMinutes($lastPunchTime, $dateTime);

            $workData = $this->workTimeData($WORK_HOURS_RENDERED , 
                $userMeta->max_work_hours , 
                $user->workHoursToday);


            /*
            *check if schedule time on in and out
            */
            $response = $this->scheduleVerify($dateTime , $schedule , $workData);

            if(!$response){
                $this->addError(" Invalid Time-in you reached your max work hours");
                return false;
            }

            $lastRow = $this->lastRow($userId);

            if( isEqual($lastRow->type , 'time_in') )

                return false;

            $this->actionTaken = 'logged in';

            return parent::store([

                'user_id' => $userId,

                'session' => get_token_random_char(30),

                'punch_time' => $dateTime,

                'type'  => 'time_in' ,

                'is_ot'  => $this->overTime

            ]);

        }



        public function clockOut($userId , $remarks = [])
        {
            $lastRow = $this->lastRow($userId);
            //set last row val
            $this->lastPunch = $lastRow;
            $user = $this->user->getMeta($userId);
            $userMeta = $user->userMeta;

            if(isEqual($lastRow->type , 'time_out')){
                return false;
            }
                
            $dateTime = nowMilitary();
            $lastPunchTime = $lastRow->punch_time;
            $WORK_HOURS_RENDERED = timeDifferenceInMinutes($lastPunchTime, $dateTime);
            

            /*if($WORK_HOURS_RENDERED < 4) {
                $this->addError(" Must render atleast 4 minutes of work");
                return false;
            }*/

            $workData = $this->workTimeData($WORK_HOURS_RENDERED , 
                $userMeta->max_work_hours , 
                $user->workHoursToday);


            $MAX_WORK_HOURS = $workData['maxWorkHours'];

            $WORK_HOURS_TODAY = $workData['workHoursToday'];

            $VALID_WORK_HOURS = $workData['validWorkHours'];

            if(is_array($remarks)) {
                $remarks = arr_to_str($remarks);
            }

            $remarks .= $workData['flushed']['msg'];

            $amount = $this->timesheet->computeSalaryWithDuration($userMeta->rate_per_hour, $VALID_WORK_HOURS);

            $this->actionTaken = 'logged out';
            
            $clockOut = parent::store([
                'user_id' => $userId,
                'session' => $lastRow->session,
                'punch_time' => $dateTime,
                'type'  => 'time_out'
            ]);

            $timeSheet = [
                'user_id' =>  $userId,
                'time_in' =>  $lastPunchTime,
                'time_out' => $dateTime,
                'duration' => $VALID_WORK_HOURS,
                'amount'   => $amount ,
                'remarks'  => $remarks,
                'status'   => 'pending' ,
                'is_ot'    => $lastRow->is_ot
            ];

            $timeSheetMeta = [
                'rate'     => $userMeta->rate_per_day,
                'clock_in_id' => $lastRow->id,
                'clock_out_id' => $clockOut
            ];
            //check total hours

            $timeSheet = $this->timesheet->save($timeSheet , $timeSheetMeta);

            $this->retVal = [
                'timeSheetId' => $this->timesheet->id,
                'timesheetMetaId' => $this->timesheet->metaId
            ];
            
            return $timeSheet;
        }





        public function punch($userId)
        {

            $lastAction = $this->lastRow($userId);

            if(!$lastAction){

                return $this->clockIn($userId);

            }else{

                if( isEqual($lastAction->type , 'time_out') )

                    return $this->clockIn($userId);

                return $this->clockOut($userId);

            }

        }

        public function lastRow($userId)
        {
            return parent::single([

                'user_id' => $userId

            ], '*' , 'id desc');

        }



        public function getActive()
        {

            $this->db->query(
                "SELECT * FROM $this->table as logs 
                    WHERE type = 'time_in' 
                    AND id not in (
                    SELECT clock_in_id from hr_time_sheet_meta
                )"
            );


            $activeList =  $this->db->resultSet();



            foreach($activeList as $key => $active) 

            {

                if($active->user_id <= 0)

                    continue;



                $active->user = $this->user->getMeta($active->user_id);

                $active->automatic_logout = $this->automaticLogout->getByUser($active->user_id);


                $active->scheduleToday = mGetSchedule($active->user_id);
                /*
                *Get user schedule today only
                *
                */

            }



            return $activeList;

        }


        //get active users this is for java access for light data access
         public function getActive_java()
        {

            $this->db->query(

                "SELECT * FROM $this->table as logs 

                    WHERE type = 'time_in' 

                    AND id not in (

                    SELECT clock_in_id from hr_time_sheet_meta

                )"

            );



            $activeList =  $this->db->resultSet();



            foreach($activeList as $key => $active) 

            {

                if($active->user_id <= 0)

                    continue;



                $active->user = $this->user->getMeta_java($active->user_id);

           }



            return $activeList;

        }


        public function log_today($today)
        {   
     
            $this->db->query(

                "SELECT * FROM users WHERE 
                    id  in (
                        SELECT user_id from hr_time_logs 
                        WHERE DATE(punch_time) >= '$today' 

                    )"
            );

            $activeList =  $this->db->resultSet();

            foreach($activeList as $key => $active) 
            {
                $this->db->query(

                    "SELECT * FROM `hr_time_logs`
                     WHERE `user_id` = '{$active->id}' and 
                     DATE(punch_time) >= '$today' 
                     ORDER BY `id` DESC LIMIT 1"
                );

                $active->clock_info =  $this->db->single();

                $active->user = $this->user->getMeta($active->id);

                $this->db->query(
                    "SELECT SUM(amount) as total ,
                     SUM(duration) as total_time
                     FROM `hr_time_sheets` 
                     WHERE `user_id` = {$active->id} 
                     AND DATE(time_in) >= '$today'  
                     AND status='approved' "
                );

                $active->total_salary =  $this->db->single()->total;
                $active->total_time =  $this->db->single()->total_time;
                $active->date_search = $today;
  
    
            }

            return $activeList;

        }

        /*
        *@parama
        *punchTime
        *schedule -> is schedule object
        */
        private function scheduleVerify($punchTime , $schedule , $workTimeData)
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
            $timeInValidation = timeDifference( $schedule->time_out ,$punchTime );
            /*
            *If positive then schedule for the day is already over
            */

            if(hoursToMinutes($timeInValidation) > 5)
            {
                $this->addLog("Shift is over");

                if(intval($workTimeData['maxWorkHours']) <= intval($workTimeData['workHoursToday'])){
                    $this->addError("You work hours has been maxed out");
                    return false;
                }

                $this->overTime = TRUE;
                return true;
            }

            return true;
        }



        /*
        *Workhours today is already in minutes
        *maxwork hours is in hour format
        *ALl datas are returned in minutes
        */
        private function workTimeData($workRenderedInMinutes, $maxWorkHours, $workHoursToday)
        {
            //in minutes
            $MAX_WORK_HOURS = hoursToMinutes($maxWorkHours);
            //inminutes
            $WORK_HOURS_TODAY = $workHoursToday;

            //initially set the valid workhours to work rendered
            $VALID_WORK_HOURS = $workRenderedInMinutes;

            //in minutes
            $ALLOWED_WORK_HOURS = $MAX_WORK_HOURS - $WORK_HOURS_TODAY;

            $flushedWorkHours = 0;

            $flushedMsg = '';

            if($workRenderedInMinutes >= $ALLOWED_WORK_HOURS)
            {
                //if the work hours surpass the valid work hours
                //then set the valid work hours
                $VALID_WORK_HOURS = $ALLOWED_WORK_HOURS;

                //get the flushed workhours or the thank you! time rendered
                $flushedWorkHours = $workRenderedInMinutes - $VALID_WORK_HOURS;


                if($flushedWorkHours != 0)
                    $flushedMsg = 'flushed work hours '.minutesToHours($flushedWorkHours).' due to your max work hours is only '.minutesToHours($MAX_WORK_HOURS);
            }

            $returnData = [
                'maxWorkHours' => $MAX_WORK_HOURS,
                'workHoursToday' => $WORK_HOURS_TODAY,
                'validWorkHours' => $VALID_WORK_HOURS,
                'flushed' => [
                    'hours' => $flushedWorkHours,
                    'msg'   => $flushedMsg
                ]
            ];

            return $returnData;
        }


        private function addLog($log)
        {
            $this->logs[] = $log;
        }

        private function getLogs()
        {
            return $this->logs;
        }
    }