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
        
        public function soryByDepartment() {
            $retVal = [];
            foreach($this->_items as $key => $row) {
                if(!isset($retVal[$row->branch_id])) {
                    $retVal[$row->branch_id] = [
                        'name' => $row->branch_name,
                        'users' => [
                            $row
                        ]
                    ];
                } else {
                    $retVal[$row->branch_id]['users'][] = $row;
                }
            }

            $this->_sortedByDepartments = $retVal;
            return $this;
        }

        
        public function getByDepartments() {
            return $this->_sortedByDepartments;
        }

        public function exportPerSheetByDepartment() {
            /**
             * The Headers
             */
            $items = [];
            $summaryPerDepartment = [];
            if(!empty($this->_sortedByDepartments)) 
            {
                $this->_spreadSheetService = new SpreadSheetExport("Payroll Period : {$this->_start} To {$this->_end}. as of {$this->_dateToday} Report by user : {$this->_user}");
                foreach($this->_sortedByDepartments as $departmentIdKey => $department) {
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
                    $items = [];
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