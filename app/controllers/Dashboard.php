<?php 
    use Services\CommonService;
    use Services\UserService;

    load(['CommonService', 'UserService'], APPROOT.DS.'services');

    class Dashboard extends Controller
    {
        public function __construct() {
            parent::__construct();
            
            $this->departmentModel = model('BranchModel');
            $this->shiftModel = model('AdminShiftModel');
            $this->employeeModel = model('UserModel');
        }

        public function index() 
        {
            $this->data['summary'] = [
                'totalDepartment' => $this->departmentModel->_getCount(),
                'totalShifts' => $this->shiftModel->_getCount(),
                'totalEmployee' => $this->employeeModel->_getCount([
                    'type' => UserService::REGULAR_EMPLOYEE
                ]),
                'totalUsers' => $this->employeeModel->_getCount([
                    'type' => [
                        'condition' => 'not equal',
                        'value' => UserService::REGULAR_EMPLOYEE
                    ]
                ])
            ];

            return $this->view('dashboard/index', $this->data);
        }

        private function _sumAllByDate($timesheets) {
            $totalWorkHours = 0;
            $daysOfWork = 0;
            $totalAmount = 0;
            CommonService::_timeSheetComputation($timesheets, $totalWorkHours, $daysOfWork, $totalAmount);

            return [
                'totalWorkHours' => $totalAmount,
                'daysOfWork' => $daysOfWork,
                'totalAmount' => $totalAmount,
            ];
        }
    }