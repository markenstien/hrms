<?php   



    class Timesheet extends Controller

    {

        public function __construct()

        {

            $this->timesheet = model('TimesheetModel');

            $this->timelog = model('TimelogModel');

            $this->taskUpload = model('TaskUploadModel');



            $this->auth = Session::get('auth');

        }

        public function index()

        {

            $timesheets = $this->timesheet->getWithMetaByUser($this->auth['id']);



            $trashedTimesheets =  $this->timesheet->getAllWithMeta([

                'is_deleted' => true,

                'user_id'   => $this->auth['id']

            ]);

               

            $parsed = [

                'timesheetsGrouped' => [],

                'workHoursToday'    => 0,

                'pendingTimesheets'  => 0

            ];



            $today = today();



            foreach($timesheets as $tk)

            {

            	$date = date_long($tk->time_in , 'M d Y');



            	if( !isset($parsed['timesheetsGrouped'][$date]) )

            		$parsed['timesheetsGrouped'][$date] = [];



            	if(isEqual($tk->status , 'pending'))

            		$parsed['pendingTimesheets']++;



            	if( isEqual($today , $date) ) 

            		$parsed['workHoursToday'] += floatval($tk->duration);



            	$parsed['timesheetsGrouped'][$date][] = $tk;

            }



            return $this->view('timesheet/index' , compact(['timesheets' , 'trashedTimesheets' , 'parsed']));

        }



        public function show($timesheetId)

        {   

            $timesheet = $this->timesheet->getWithMeta($timesheetId);

            $sheetMeta = $timesheet->meta;



            $logs = $this->timelog->all(" id in ('{$sheetMeta->clock_in_id}' , '{$sheetMeta->clock_out_id}') ");



            $taskPhotos = $this->taskUpload->all([

                'log_id' => $sheetMeta->clock_in_id

            ]);



            $data = [

                'timesheet' => $timesheet,

                'logs'      => $logs,

                'taskPhotos' => $taskPhotos

            ];

            return $this->view('timesheet/show' , $data);

        }

        public function search($id)
        {

            $timesheets = $this->timesheet->getByUserWeek($id);

            $trashedTimesheets =  $this->timesheet->getAllWithMeta([
                'is_deleted' => true,
                'user_id'   => $id
            ]);

            
            $parsed = [
                'timesheetsGrouped' => [],
                'workHoursToday'    => 0,
                'pendingTimesheets'  => 0
            ];

            $today = today();

            foreach($timesheets as $tk)
            {

                $date = date_long($tk->time_in , 'M d Y');

                if( !isset($parsed['timesheetsGrouped'][$date]) )
                    $parsed['timesheetsGrouped'][$date] = [];


                if(isEqual($tk->status , 'pending'))
                    $parsed['pendingTimesheets']++;


                if( isEqual($today , $date) ) 
                    $parsed['workHoursToday'] += floatval($tk->duration);


                $parsed['timesheetsGrouped'][$date][] = $tk;
            }

            return $this->view('timesheet/index' , compact(['timesheets' , 'trashedTimesheets' , 'parsed']));
        }

    }