<?php 
    use Services\CommonService;
    load(['CommonService'], APPROOT.DS.'services');

    class Dashboard extends Controller
    {
        public function __construct() {
            parent::__construct();
        }

        public function index() 
        {
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