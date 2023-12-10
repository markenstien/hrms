<?php   

    class TimesheetAction extends Controller
    {

        public function __construct()
        {
            $this->timesheet = model('TimesheetModel');
            $this->user  = model('UserModel');
        }

        public function index()
        {
            $post = request()->posts();

            if(isset($_POST['bulk_action_btn']))
            {
                if( isEqual($_POST['bulk_action'] , 'approve') ) {

                    $response = $this->timesheet->approveBulk($post['timesheet_ids']);

                    if($response) {
                        Flash::set("Total of ".count($this->timesheet->results). " Timesheets has been approved");
                    }
                }
            }

            return request()->return();
        }

        public function approve($timesheetId)
        {
            $token = $_GET['token'];

            if(isEqual(seal($timesheetId)  , $token))
            {
                $response = $this->timesheet->approve($timesheetId);

                if($response) {
                    Flash::set("Timesheet approved");
                }

                if(isset($_GET['next'])){
                    $last = $this->timesheet->getLatest();
                    return redirect("timesheet/show/{$last->id}");
                }

              
            }

            return request()->return();
        }

        public function update_timesheet()
        {
            $post = request()->posts();
        
            $user = $this->user->getMeta($post['userid']);

            $userMeta = $user->userMeta;

            $result = $this->timesheet->update_cancelled_timesheet($userMeta, $post);

            Flash::set("Updated Successfully");
            return request()->return();

        }
    }