<?php 

    class Attendance extends Controller
    {
        public $model, $timelogPlusModel;

        public function __construct()
        {
            parent::__construct();
            $this->model = model('AttendanceModel');
            $this->timelogPlusModel = model('TimelogPlusModel');
        }

        public function getStatus() {
            $req = request()->inputs();

            if(!empty($req['userId'])) {
                $lastLog = $this->timelogPlusModel->getLastLog($req['userId']);
                $timelogAction = $this->timelogPlusModel->typeOfAction($lastLog);

                echo json_encode([
                    'data' => [
                        'lastLog' => $lastLog,
                        'timelogAction' => $timelogAction
                    ],
                    'success' => true,
                    'message' => 'ok'
                ]);

                return;
            } else {
                echo json_encode([
                    'data' => '',
                    'success' => false,
                    'message' => 'Invalid Request'
                ]);
            }
        }

        public function getList() {
            $req = request()->inputs();

            if(!empty($req['userId'])) {
                $attendanceList = $this->model->getAll([
                    'order' => 'id desc',
                    'where' => [
                        'timesheet.user_id' => $req['userId']
                    ]
                ]);

                echo json_encode([
                    'data' => $attendanceList,
                    'success' => true,
                    'message' => 'List of Attendance'
                ]);
            } else {
                echo json_encode([
                    'data' => '',
                    'success' => false,
                    'message' => 'Invalid Request'
                ]);
            }
        }
    }