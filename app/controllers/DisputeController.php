<?php 

    class DisputeController extends Controller
    {
        public $category;

        public function __construct()
        {
            $this->time_meta_model = model('TimelogMetaModel');
            $this->timesheet_model = model('TimesheetModel');
            $this->user_model = model('UserModel');


            $this->category = [
                'OT',
                'TIMECARD ISSUE',
                'UNPAID RENDERED WORK'
            ];
        }

        public function index()
        {
            $viewFilter = request()->input('dispute_type') ?? '';
            $request = request()->inputs();

            $users = $this->user_model->dbgetAssoc('firstname');
            $issues = [];

            switch($viewFilter)
            {
                case 'issues':
                    $issues = $this->getIssues($request);
                    break;
                
            }
            $data = [
                'issues' => $issues,
                'users'  => arr_layout_keypair($users, ['id' ,'firstname@lastname'])
            ];

            return $this->view('dispute/index' , $data);
        }

        private function getIssues($request)
        {
            $retVal = [];
            $condition = [
                'tklog.created_at' => [
                    'condition' => 'between',
                    'value'     => [$request['start_date'] , $request['end_date']],
                    'concatinator' => ' AND '
                ],
                'tklog.flushed_hours' => [
                    'condition' => '>',
                    'value' => 0,
                    'concatinator' => ' OR '
                ],
                'tklog.status' => [
                    'condition' => 'equal',
                    'value' => $this->timesheet_model::$STATUS_CANCEL
                ]
            ];

            $timesheets = $this->timesheet_model->getWithDisputes([
                'startDate' => $request['start_date'],
                'endDate'   => $request['end_date']
            ]);

            if (!empty($request['user_id'])) {
                foreach($timesheets as $key => $row) {
                    if (isEqual($row->user_id , $request['user_id'])) {
                        $retVal[] = $row;
                    }
                }
            } else {
                $retVal = $timesheets;
            }
            return $retVal;
        }

        public function create()
        {
            $users = $this->user_model->dbgetAssoc('firstname');

            $data = [
                'users' => arr_layout_keypair($users , ['id' , 'firstname@lastname']),
                'title' => 'Create Dispute',
                'category' => $this->category
            ];
            return $this->view('dispute/create' , $data);
        }
    }