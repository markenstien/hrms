<?php
    use Form\AttendanceForm;
    use Services\QRTokenService;
    use Services\SpreadSheetImport;
    use Services\TimesheetService;

    load(['SpreadSheetImport', 'TimesheetService', 'QRTokenService'], SERVICES);
    load(['AttendanceForm'], FORMS);

    class AttendanceController extends Controller
    {
        public $form, $model;

        public function __construct()
        {
            parent::__construct();
            $this->form = new AttendanceForm();
            $this->model = model('AttendanceModel');
            $this->timelogPlusModel = model('TimelogPlusModel');
            $this->userModel = model('UserModel');
            $this->data['form'] = $this->form;
        }

        public function index() {
            if(isEqual(whoIs('type'), 'REGULAR_EMPLOYEE')) {
                $this->data['attendanceList'] = $this->model->getAll([
                    'order' => 'id desc',
                    'where' => [
                        'timesheet.user_id' => whoIs('id')
                    ]
                ]);
            } else {
                $this->data['attendanceList'] = $this->model->getAll([
                    'order' => 'id desc'
                ]);
            }
            
            $lastLog = $this->timelogPlusModel->getLastLog(whoIs('id'));
            $timelogAction = $this->timelogPlusModel->typeOfAction($lastLog);

            $loginToken = QRTokenService::getLatestToken(QRTokenService::LOGIN_TOKEN);
            $this->data['timelog'] = [
                'action' => $timelogAction,
                'last' => $lastLog,
                'urlAction' => QRTokenService::getLink($loginToken, [
                    'token' => $loginToken,
                    'device' => 'web',
                    'userId' => whoIs('id'),
                    'route' => _route('attendance:index')
                ])
            ];

            return $this->view('attendance/index', $this->data);
        }

        /**
         * timesheets that are for approval
         * can be seen here, managers can approve the lists
         * they will have special button
         */
        public function approval() {
            $req = request()->inputs();

            if(isset($req['action'])) {
                switch($req['action']) {
                    case 'approve':
                        $this->model->approve(unseal($req['timesheet']), $req['userId']);
                    break;

                    case 'cancel':
                        $this->model->cancel(unseal($req['timesheet']), $req['userId']);
                    break;
                }
            }
            $timesheets = $this->model->getAll([
                'where' => [
                    'status' => 'pending'
                ]
            ]);

            $this->data['timesheets'] = $timesheets;
            return $this->view('attendance/approval', $this->data);
        }

        public function create() {
            $req = request()->inputs();

            if(isSubmitted()) {
                $post = request()->posts();
                $post['created_by'] = whoIs('id');

                if(!empty($post['uid'])) {
                    //search user
                    $user = $this->userModel->getByKey('uid', $post['uid'])[0] ?? false;

                    if(!$user) {
                        Flash::set("No user with such ID '{$post['uid']}' ", 'warning');
                        return request()->return();
                    } else {
                        $post['user_id'] = $user->id;
                    }
                }

                $isOk = $this->model->manualEntry($post);
                
                if(!$isOk) {
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                } else {
                    Flash::set("Attendance Form Submitted");
                }

                return redirect(_route('attendance:index'));
            }
            $this->form->setValue('user_id', whoIs('id'));
            $this->data['form'] = $this->form;
            return view('attendance/create', $this->data);
        }

        public function import() {

            if(isSubmitted()) {
                $resp = upload_document('timesheet_file', PATH_UPLOAD.DS.'timesheets', ['csv']);

                if($resp['status'] == 'failed') {
                    Flash::set($resp['result']['err'], 'danger');
                    return request()->return();
                }

                $pathToImport = PATH_UPLOAD.DS.'timesheets/'.$resp['result']['name'];

                $post = request()->posts();
                $spreadSheetImport = new SpreadSheetImport();
                $returnData = $spreadSheetImport->import($pathToImport);
                $timesheetService = new TimesheetService();
                $timesheets = $timesheetService->importToDb($returnData);

                $this->data['timesheets'] = $timesheets;
                $this->data['file_name'] = seal($resp['result']['name']);

                return $this->view('attendance/import_review', $this->data);
            }

            return $this->view('attendance/import', $this->data);
        }

        public function saveAndImport() {
            $req = request()->inputs();

            if(!empty($req['path'])) {
                $pathName = trim(unseal($req['path']));
                $pathToImport = PATH_UPLOAD.DS.'timesheets/'.$pathName;

                $spreadSheetImport = new SpreadSheetImport();
                $returnData = $spreadSheetImport->import($pathToImport);
                $timesheetService = new TimesheetService();
                $timesheets = $timesheetService->importToDb($returnData);

                $timesheetsToImport = [];
                foreach($timesheets as $date => $timelogs) {
                    foreach($timelogs as $log) {
                        if(!is_null($log['user_id']) && !empty($log['duration_in_minutes'])) {
                            $timesheetsToImport[] = $log;
                        }
                    }
                }

                foreach($timesheetsToImport as $key => $row) {
                    $resp = $this->model->manualEntry([
                        'start_date' => $row['date'],
                        'time_in' => $row['in'],
                        'end_date' => $row['date'],
                        'time_out' => $row['out'],
                        'user_id' => $row['user_id'],
                        'entry_type' => 'csv timesheet import'
                    ]);
                    $this->model->approve($this->model->_getRetval('attendanceId'), whoIs('id'));
                }

                Flash::set("Attendance Imported");
                return redirect(_route('attendance:index'));
            }
        }

        public function loggedIn() {
            $this->data['loggedUsers'] = $this->timelogPlusModel->getOngoing();
            $this->data['QRTokenService'] = new QRTokenService;
            $this->data['token'] = QRTokenService::getLatestToken(QRTokenService::LOGIN_TOKEN);
            return $this->view('attendance/logged_in', $this->data);
        }
    }