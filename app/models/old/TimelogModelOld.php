<?php   



    class TimelogModel extends Model

    {

        public $table = 'hr_time_logs';

        public $logs = []; //can be posted on timesheets or time logs

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
            $dateTime = todayMilitary();
            $schedule = mGetSchedule($userId);

            if($schedule->is_off){
                $this->addError(" Today is your day off");
                return false;
            }
            /*
            *check if schedule time on in and out
            */
            $response = $this->scheduleVerify($dateTime , $schedule);

            $lastRow = $this->lastRow($userId);

            

            if( isEqual($lastRow->type , 'time_in') )

                return false;

            $this->actionTaken = 'logged in';

            return parent::store([

                'user_id' => $userId,

                'session' => get_token_random_char(30),

                'punch_time' => $dateTime,

                'type'  => 'time_in'

            ]);

        }



        public function clockOut($userId)
        {

            $lastRow = $this->lastRow($userId);



            $user = $this->user->getMeta($userId);

            

            $userMeta = $user->userMeta;



            if( isEqual($lastRow->type , 'time_out') )

                return false;



            $dateTime = todayMilitary();


            //work rendered upon clocking out
            $durationInMinutes = timeDifferenceInMinutes( $lastRow->punch_time, $dateTime );

            //in hours needs to convert in minutes

            $MAX_WORK_HOURS = hoursToMinutes($userMeta->max_work_hours);

            //already in minutes

            $WORK_HOURS_TODAY = $user->workHoursToday;//20mins


            $WORK_HOURS_RENDERED = timeDifferenceInMinutes($lastRow->punch_time, $dateTime);

            $workData = $this->workTimeData($WORK_HOURS_RENDERED , 
                $userMeta->max_work_hours , 
                $user->workHoursToday);



            $MAX_WORK_HOURS = $workData['maxWorkHours'];
            $WORK_HOURS_TODAY = $workData['workHoursToday'];


            //allowed work hours in minutes

            $ALLOWED_WORK_HOURS = $MAX_WORK_HOURS - $WORK_HOURS_TODAY; //30mins - 20mins = 10mins

            //set valid workhours to current duration
            $VALID_WORK_HOURS = $durationInMinutes;

            //if duration in minutes is lwesser allowed max work hours

            

            $remarks = '';

            //then set the valid work hours
            //depending on the situation

            if( $durationInMinutes >= $ALLOWED_WORK_HOURS )
            {

                //set the valid work hours to allowed hours
                //if the work hours surpass the valid work hours
                $VALID_WORK_HOURS = $ALLOWED_WORK_HOURS;

                //get the flushed workhours
                $flusedWorkHours = $durationInMinutes - $VALID_WORK_HOURS;

                if($flusedWorkHours != 0)
                    $remarks = 'flushed work hours '.minutesToHours($flusedWorkHours).' due to your max work hours is only '.minutesToHours($MAX_WORK_HOURS);

            }



            $this->actionTaken = 'logged out';

          

            $clockOut = parent::store([

                'user_id' => $userId,

                'session' => $lastRow->session,

                'punch_time' => $dateTime,

                'type'  => 'time_out'

            ]);

                



            $timeSheet = [

                'user_id' =>  $userId,

                'time_in' =>  $lastRow->punch_time,

                'time_out' => $dateTime,

                'duration' => $VALID_WORK_HOURS,

                'amount'   => $this->timesheet->computeSalaryWithDuration($userMeta->rate_per_hour, $VALID_WORK_HOURS),

                'remarks'  => $remarks,

                'status'   => 'pending'

            ];



            $timeSheetMeta = [

                'rate'     => $userMeta->rate_per_day,

                'clock_in_id' => $lastRow->id,

                'clock_out_id' => $clockOut

            ];



            //check total hours

            $timeSheet = $this->timesheet->save($timeSheet , $timeSheetMeta);



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

                /*
                *Get user schedule today only
                *
                */

            }



            return $activeList;

        }

        /*
        *@parama
        *punchTime
        *schedule -> is schedule object
        */
        private function scheduleVerify($punchTime , $schedule)
        {
            //check if early time-in
            $timeInValidation = timeDifference($schedule->time_in , $punchTime);
            /*
            *If negative that means the punchtime is too early 
            *for the users schedule
            */
            if($timeInValidation < 0){
                $this->addError("Too early for shift");
                return false;
            }

            //check over time time-in
            $timeInValidation = timeDifference($schedule->time_out , $punchTime);
            /*
            *If positive then schedule for the day is already over
            */

            // dump([
            //     $timeInValidation,
            //     time_short($schedule->time_out),
            //     $punchTime
            // ]);

            if($timeInValidation > 0){

                //check if can ot

                $CURRENT_WORK_HOURS = 0;
                $MAX_WORK_HOURS = 0;


                $this->addError("Shift is over");
                return false;
            }

            return true;
        }



        /*
        *Workhours today is already in minutes
        *maxwork hours is in hour format
        */
        private function workTimeData($workRenderedInMinutes, $maxWorkHours, $workHoursToday)
        {
            $returnData = [];

            $MAX_WORK_HOURS = hoursToMinutes($maxWorkHours);

            $WORK_HOURS_TODAY = $workHoursToday;

            //initially set the valid workhours to work rendered
            $VALID_WORK_HOURS = $workRenderedInMinutes;


            $flushedWorkHours = 0;

            $flushedMsg = '';

            if($workRenderedInMinutes >= $ALLOWED_WORK_HOURS)
            {
                //if the work hours surpass the valid work hours
                //then set the valid work hours
                $VALID_WORK_HOURS = $ALLOWED_WORK_HOURS;

                //get the flushed workhours or the thank you! time rendered
                $flushedWorkHours = $durationInMinutes - $VALID_WORK_HOURS;


                if($flusedWorkHours != 0)
                    $flushedMsg = 'flushed work hours '.minutesToHours($flushedWorkHours).' due to your max work hours is only '.minutesToHours($MAX_WORK_HOURS);
            }

            return [
                'maxWorkHours' => $MAX_WORK_HOURS,
                'workHoursToday' => $WORK_HOURS_TODAY,
                'validWorkHours' => $VALID_WORK_HOURS,
                'flushed' => [
                    'hours' => $flushedWorkHours,
                    'msg'   => $flushedMsg
                ]
            ];
        }

    }