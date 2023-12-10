<?php   



    class Timesheet extends Controller

    {

        

        public function __construct()

        {

            $this->timesheet = model('TimesheetModel');

            $this->timelog = model('TimelogModel');

            $this->taskUpload = model('TaskUploadModel');

            $this->user = model('UserModel');

        }



        public function index()

        {

            $status = $_GET['status'] ?? '';

           

            $timesheets = [];

            

            switch(strtolower($status))

            {

                case 'approved':

                    $timesheets = $this->timesheet->getAllWithMeta([

                        'status' => 'approved',

                        'is_deleted' => false

                    ]);

                break;

                case 'pending':

                    $timesheets = $this->timesheet->getAllWithMeta([

                        'status' => 'pending',

                        'is_deleted' => false

                    ]);

                break;

                default:

                    $timesheets = $this->timesheet->getActive();

            }

            ee(api_response($timesheets));

        }





        public function show($id)

        {



            $id = $_GET['id'];



            if(empty($id))

            {

                ee(api_response('invalid request' , false));

            }else

            {

                $timesheet = $this->timesheet->getWithMeta($id);



                $sheetMeta = $timesheet->meta;



                $logs = $this->timelog->all(" id in ('{$sheetMeta->clock_in_id}' , '{$sheetMeta->clock_out_id}') ");



                $taskPhotos = $this->taskUpload->all([

                    'log_id' => $sheetMeta->clock_in_id

                ]);



                $data = [

                    'timesheet' => $timesheet,

                    'logs'      => $logs,

                    'taskPhotos' => $taskPhotos,

                    'account'      => $this->user->get($timesheet->user_id)

                ];



                ee(api_response($data));

            }

            

        }

        

        //post only

        public function approve()

        {

            $post = request()->inputs();



            $timesheet = $this->timesheet->approve($post['id']);

            

            $latestTimesheet = $this->timesheet->getLatest();



            if($timesheet) {

                ee(api_response($latestTimesheet));

            }else{

                ee(api_response('something went wrong' , false));

            }

        }

        //post only

        public function delete()

        {

            $post = request()->inputs();



            $timesheet = $this->timesheet->delete($post['id']);



            if($timesheet) {

                ee(api_response('timesheet deleted'));

            }else{

                ee(api_response('something went wrong' , false));

            }

        }



        public function deleteBulk()

        {

            $post = request()->inputs();



            $result = $this->timesheet->deleteBulk($post['timesheetIds']);



            if($result) {

                ee(api_response('bulk delete success'));

            }else{

                ee(api_response('bulk delete failed' , false));

            }

        }





        public function approveBulk()

        {

            $post = request()->inputs();



            $result = $this->timesheet->approveBulk($post['timesheetIds']);



            if($result) {

                ee(api_response('bulk approved success'));

            }else{

                ee(api_response('bulk approved failed' , false));

            }

        }



        public function moveToTrash()

        {

            $post = request()->inputs();



            $result = $this->timesheet->moveToTrash($post['timesheetIds']);



            if($result) {

                ee(api_response("moved to trash"));

            }else{

                ee(api_response("move to trash failed"));

            }

        }



        public function restore()

        {

            $post = request()->inputs();



            $restore = $this->timesheet->restore($post['timesheetIds']);



            if($restore) {

                ee(api_response('Timesheet Restored'));

            }else{

                ee(api_response('Timesheet error'));

            }

        }



        public function trash()

        {

            $results = $this->timesheet->getTrash();



            ee(api_response($results));

        }

    }