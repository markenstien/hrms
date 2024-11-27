<?php 
    namespace Services;
    
    use Services\SpreadSheetExport;
    load(['SpreadSheetExport'], APPROOT.DS.'services');

    class PayrollService {
        public $_items = [];
        private $_sortedByDepartments = [];
        private $_spreadSheetService;
        private $_start;
        private $_end;

        private $_dateToday, $_user;

        public function __construct()
        {
            $this->_dateToday = now();
            $this->_user = whoIs()['firstname'] . ' '.whoIs()['lastname'];
        }

        public function setPayrollPeriod($start,$end) {
            $this->_start = $start;
            $this->_end = $end;
            return $this;
        }

        public function setItems($items = []) {
            $this->_items = $items;
            return $this;
        }
        
        public function getItems() {
            return $this->_items;
        }
        public function soryByDepartment() {
            $retVal = [];
            foreach($this->_items as $key => $row) {
                if(!isset($retVal[$row->department_id])) {
                    $retVal[$row->department_id] = [
                        'name' => $row->department_name,
                        'users' => [
                            $row
                        ]
                    ];
                } else {
                    $retVal[$row->department_id]['users'][] = $row;
                }
            }

            $this->_sortedByDepartments = $retVal;
            return $this;
        }

        
        public function getByDepartments() {
            return $this->_sortedByDepartments;
        }

        public function export() {
            $items = [];
            foreach($this->getItems() as $key => $item) {
                $items[] = [
                        'Staff',
                        'No of work Days',
                        'Hours Worked',
                        'Take Home Pay'
                    ];
            }
        }

        public function exportPerSheetByDepartment() {
            /**
             * The Headers
             */
            $items = [];
            $summaryPerDepartment = [];

            $departmentItems = $this->getByDepartments();
            if(!empty($departmentItems)) 
            {
                $this->_spreadSheetService = new SpreadSheetExport("PERIOD_{$this->_start}_{$this->_end}_OF_{$this->_dateToday}_CREATOR_{$this->_user}");
                foreach($departmentItems as $departmentIdKey => $department) {
                    /**
                     * Initiate Headers
                     */
                    $items[] = [
                        'Staff',
                        'No of work Days',
                        'Hours Worked',
                        'Take Home Pay'
                    ];
                    $departmentName = $department['name'];
                    $users = $department['users'];

                    foreach($users as $key => $user) {
                        array_push($items, [
                            $user->fullname,
                            $user->no_of_days,
                            minutesToHours($user->reg_hours_total),
                            number_format($user->take_home_pay, 2)
                        ]);
                    }
                    $this->_spreadSheetService->setItems($items, $departmentName);

                    $itemSummary = $this->calculateSummary($users);

                    $summaryPerDepartment[] = [
                        'departmentName' => $departmentName,
                        'totalHoursWorked' => $itemSummary['totalHoursWorked'],
                        'totalAmount' => $itemSummary['totalAmount'],
                        'NoOfPeople' => $itemSummary['NoOfPeople']
                    ];

                    //reset headers
                    // $items = [];
                }

                /**
                 * Overall
                 */
                $overAllItems = [[
                    'Department Name',
                    'No Of People',
                    'Total Hours',
                    'Total Amount',
                ]];

                $overAllTotalAmount = 0;

                foreach($summaryPerDepartment as $key => $row) {
                    array_push($overAllItems, [
                        $row['departmentName'],
                        $row['NoOfPeople'],
                        $row['totalHoursWorked'],
                        $row['totalAmount'],
                    ]);

                    $overAllTotalAmount += $row['totalAmount'];
                }

                array_push($overAllItems, [
                    'Over All Company Payout',
                    '',
                    '',
                    $overAllTotalAmount
                ]);

                $this->_spreadSheetService->setItems($overAllItems, "Summary");
                $this->_spreadSheetService->setActiveWorkSheet("Summary");
                $this->_spreadSheetService->export();
            } else {
                return false;
            }
        }

        private function calculateSummary($users) {
            $totalHoursWorked = 0;
            $totalAmount = 0;
            $NoOfPeople = 0;

            if($users) {
                foreach($users as $key => $row) {
                    $totalAmount += $row->take_home_pay;
                    $totalHoursWorked += $row->reg_hours_total;
                }
    
                $NoOfPeople = count($users);
            }

            return [
                'totalHoursWorked' => $totalHoursWorked,
                'totalAmount'  => $totalAmount,
                'NoOfPeople'   => $NoOfPeople
            ];
        }
        
    }