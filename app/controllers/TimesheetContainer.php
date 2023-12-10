<?php   
    use Services\CommonService;
    use Services\SpreadSheetExport;

    load(['CommonService','SpreadSheetExport'], APPROOT.DS.'services');

    class TimesheetContainer extends Controller
    {

        public function __construct()
        {
            $this->timesheet = model('TimesheetModel');
        }

        public function index()
        {
            $req = request()->inputs();
            $data = [
                'timesheets' => $this->timesheet->getAllWithMeta([
                    'status' => 'pending',
                    'is_deleted' => false
                ])
            ];
            return $this->view('timesheet_container/index' , $data);
        }

        public function cancelled_timeSheet()
        {   
            $req = request()->inputs();
            date_default_timezone_set("Asia/Manila");

            $today = date("Y-m-d");
            
            $condition = [
                'date' => $today
            ];

            if(!empty($req['branch_id'])) {
                $condition['branch_id'] = $req['branch_id'];
            }

            $data = [
                'timesheets' => $this->timesheet->get_cancelled_timesheet($condition)
            ];
            return $this->view('timesheet_container/cancelled' , $data);
        }

        public function approveTimeSheets() {
            if(isSubmitted()) {
                $post = request()->posts();
                $timeSheetIds = unseal($post['timesheet_ids']);

                if($timeSheetIds) {
                    $isOkay = $this->timesheet->approveBulk($timeSheetIds);
                    if($isOkay) {
                        Flash::set("(".count($this->timesheet->results).") timesheets has been approved");
                    } else {
                        Flash::set("Something went wrong", 'danger');
                    }
                } else {
                    Flash::set("There are timesheets to be approved");
                }
            }

            return request()->return();
        }

        public function approved()
        {   
            $limit = null;
            
            if (isset($_GET['action']) && $_GET['action'] == 'today') 
            {
                $dataFilter = [
                    'tklog.status' => 'approved',
                    'tklog.is_deleted' => false,
                    'date(tklog.time_in)' => [
                        'condition' => 'equal',
                        'value' => today()
                    ]
                ];
            }else if(isset($_GET['btn_filter']))
            {
                $dataFilter = [
                    'tklog.status' => 'approved',
                    'tklog.is_deleted' => false,
                    'date(tklog.time_in)' => [
                        'condition' => 'between',
                        'value' => [$_GET['start_date'],$_GET['end_date']]
                    ]
                ];

            }else{
                $dataFilter = [
                    'tklog.status' => 'approved',
                    'tklog.is_deleted' => false
                ];
            }
            
            $timesheets = $this->timesheet->getAll($dataFilter, null, $limit);

            $timesheetsGrouped = $this->timesheet->groupResultByUser($timesheets);
            $timesheetsGroupedByBranch = $this->timesheet->groupByBranch($timesheetsGrouped);

            $data = [
                'timesheets' => $timesheets,
                'timesheetsGrouped' => $timesheetsGrouped,
                'timesheetsGroupedByBranch' => $timesheetsGroupedByBranch
            ];
    

            return $this->view('timesheet_container/approved' , $data);
        }

        public function weekly()
        {
            $limit = null;
            //initial fetch this month only
            $request = request()->inputs();

            if (isset($request['action']) && $request['action'] == 'today') 
            {
                $dataFilter = [
                    'tklog.status' => 'approved',
                    'tklog.is_deleted' => false,
                    'date(tklog.time_in)' => [
                        'condition' => 'equal',
                        'value' => today()
                    ]
                ];
            }else if(isset($request['btn_filter']))
            {
                $dataFilter = [
                    'tklog.status' => 'approved',
                    'tklog.is_deleted' => false,
                    'date(tklog.time_in)' => [
                        'condition' => 'between',
                        'value' => [$request['start_date'],$request['end_date']]
                    ]
                ];
            }else{
                $dataFilter = [
                    'tklog.status' => 'approved',
                    'tklog.is_deleted' => false
                ];
            }
            $timesheets = $this->timesheet->getAll($dataFilter, "day(tklog.time_in) asc" , $limit);

            $tsheetsGroupByUser = $this->timesheet->groupSheetsByUser($timesheets);
            $tsheetsGroupByUserGroupedByDays = $this->timesheet->groupUserSheetsByDays($tsheetsGroupByUser);
            
            $groupedByBranch = [];
            /**
             * group timesheetsdays by branch
             */
            foreach ($tsheetsGroupByUserGroupedByDays as $key => $row) {

                if (!isset($groupedByBranch[$row['branch_id']])) {
                    $groupedByBranch[$row['branch_id']] = [
                        'name' => $row['branch_name'],
                        'users' => []
                    ];
                }
                $groupedByBranch[$row['branch_id']]['users'][] = $row;
            }

            $displayOption  = 'grouped_by_user';

            if(isset($request['display_option']) && isEqual($request['display_option'], 
            'weekly_by_branch')){
                $displayOption = 'grouped_user_by_branch';
            }

            if(!empty($request['exportData'])) 
            {
                if (isEqual($request['display_option'],'weekly_by_branch')) {
                    $this->exportPerBranch($groupedByBranch);
                } else {
                    $this->exportPerUser($tsheetsGroupByUserGroupedByDays);
                }
            }

            return $this->view('timesheet_container/' . $displayOption  ,[
                'timesheets' => $timesheets,
                'tsheetsGroupByUserGroupedByDays' => $tsheetsGroupByUserGroupedByDays,
                'groupedByBranch' => $groupedByBranch,
            ]);
        }

        public function monthly() 
        {
            $data = [
                'title' => 'Monthly Filter'
            ];

            $req = request()->inputs();
            if (isset($req['montly_filter'])) {
                $dataFilter = [
                    'tklog.status' => 'approved',
                    'tklog.is_deleted' => false,
                    'date(tklog.time_in)' => [
                        'condition' => 'between',
                        'value' => [$_GET['start_date'],$_GET['end_date']]
                    ]
                ];
                $timesheets = $this->timesheet->getAll($dataFilter, null);
                $timesheetsGrouped = $this->timesheet->groupResultByUser($timesheets);

                $tsheetsGroupByUser = $this->timesheet->groupSheetsByUser($timesheets);
                $tsheetsGroupByUserGroupedByDays = $this->timesheet->groupUserSheetsByDate($tsheetsGroupByUser);

                $startDate = $req['start_date'];
                $endDate = $req['end_date'];

                $movingDate = $startDate;
                $datesCreated = [];

                while($movingDate <= $endDate) {
                    array_push($datesCreated, $movingDate);
                    $movingDate = date('Y-m-d',strtotime('+1 day '.$movingDate));
                }

                $data['datesCreated'] = $datesCreated;
                $data['timesheetsByUsers'] = $tsheetsGroupByUser;
                $data['timesheetsByUsersGroupedByDates'] = $tsheetsGroupByUserGroupedByDays;
                $data['timesheetsGrouped'] = $timesheetsGrouped;
            }

            return $this->view('timesheet_container/monthly', $data);
        }

        public function yearly() {

            $req = request()->inputs();

            if(!isset($this->userModel)) {
                $this->userModel = model('UserModel');
            }

            if(!empty($req['btn_result'])) {
                $users = $this->userModel->getAll([
                    'where' => [
                        'is_deleted' => false,
                    ],
                    'order' => 'firstname asc'
                ]);

                $timesheets = [];
                if($users) {
                    foreach($users as $user) {
                        $this->timesheet->db->query(
                            "SELECT 
                                SUM(amount) as total,
                                SUM(duration) as total_duration,
                                MONTHNAME(time_in) as month_name
                                FROM {$this->timesheet->table}
                                    WHERE user_id = '{$user->id}'
                                    AND year(time_in) = '{$req['year']}'
                                    AND status = 'approved'
                                GROUP BY MONTHNAME(time_in)
                                ORDER BY month(time_in) asc"
                        );

                        $timesheetResults = $this->timesheet->db->resultSet();
                        $timesheets[$user->id] = [
                            'name' => $user->firstname . ' '.$user->lastname,
                            'timesheets' => $timesheetResults
                        ];
                    }
                }

                $data['run'] = true;
            } else {
                $timesheets = [];
            }
            
            $data['timesheets'] = $timesheets;

            $data['months'] = CommonService::months();
            $data['req'] = $req;

            return $this->view('timesheet_container/yearly', $data);
        }

        public function logs()
        {
            $dataFilter = [
                'tklog.status' => 'approved',
                'tklog.is_deleted' => false
            ];

            $logs = $this->timesheet->getAll($dataFilter, "tklog.time_in desc" , 250);

            $data = [
                'logs' => $logs,
                'title' => 'Timesheet Logs'
            ];
            return $this->view('timesheet_container/logs',$data);
        }

        private function extractTimesheet($timesheets) 
        {
            $duration = 0;
            $amount = 0;

            foreach($timesheets as $key => $row) {
                $duration += $row->duration;
                $amount += $row->amount;
            }

            return [
                'totalAmount' => $amount,
                'stringResult' =>  minutesToHours($duration)."({$amount})"
            ];
        }

        private function exportPerBranch($groupedByBranch) {
            //prepare export
            $spreadSheetExport = new SpreadSheetExport('weekly_report');
            $exportHeaders = [
                'Name',
                'Rate',
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
                'Sun',
                'Total'
            ];

            $items = [];

            $overAllTotal = 0;
            foreach ($groupedByBranch as $key => $branch) 
            {
                $totalAmount = 0;
                if(empty($branch['name'])) {
                    continue;
                }

                array_push($items, [
                    $branch['name']
                ]);

                array_push($items,$exportHeaders);

                foreach($branch['users'] as $key => $user) 
                {
                    $addToItems = [];
                    $timesheetDays = $user['timesheetByDays'];
                    $totalIncome = 0;
                    if(empty($user['fullname'])) continue;

                    array_push($addToItems, $user['fullname']);
                    array_push($addToItems, number_format($user['rate_per_day'],2));

                    if(isset($timesheetDays['Mon'])) {
                       $extractedData = $this->extractTimesheet($timesheetDays['Mon']);
                       array_push($addToItems, $extractedData['stringResult']);
                       $totalIncome += $extractedData['totalAmount'];
                    } else {
                        array_push($addToItems,'N/A');
                    }

                    if(isset($timesheetDays['Tue'])) {
                       $extractedData = $this->extractTimesheet($timesheetDays['Tue']);
                       array_push($addToItems, $extractedData['stringResult']);
                       $totalIncome += $extractedData['totalAmount'];
                    } else {
                       array_push($addToItems,'N/A');
                    }

                    if(isset($timesheetDays['Wed'])) {
                        $extractedData = $this->extractTimesheet($timesheetDays['Wed']);
                        array_push($addToItems, $extractedData['stringResult']);
                        $totalIncome += $extractedData['totalAmount'];
                    } else {
                        array_push($addToItems,'N/A');
                    }

                    if(isset($timesheetDays['Thu'])) {
                       $extractedData = $this->extractTimesheet($timesheetDays['Thu']);
                       array_push($addToItems, $extractedData['stringResult']);
                       $totalIncome += $extractedData['totalAmount'];
                    } else {
                        array_push($addToItems,'N/A');
                    }

                    if(isset($timesheetDays['Fri'])) {
                       $extractedData = $this->extractTimesheet($timesheetDays['Fri']);
                       array_push($addToItems, $extractedData['stringResult']);
                       $totalIncome += $extractedData['totalAmount'];
                    } else {
                       array_push($addToItems,'N/A');
                    }

                    if(isset($timesheetDays['Sat'])) {
                       $extractedData = $this->extractTimesheet($timesheetDays['Sat']);
                       array_push($addToItems, $extractedData['stringResult']);
                       $totalIncome += $extractedData['totalAmount'];
                    } else {
                        array_push($addToItems,'N/A');
                    }

                    if(isset($timesheetDays['Sun'])) {
                        $extractedData = $this->extractTimesheet($timesheetDays['Sun']);
                        array_push($addToItems, $extractedData['stringResult']);
                        $totalIncome += $extractedData['totalAmount'];
                    } else {
                        array_push($addToItems,'N/A');
                    }

                    array_push($addToItems, $totalIncome);
                    array_push($items, $addToItems);

                    $totalAmount += $totalIncome;
                }
                $overAllTotal += $totalAmount;
                array_push($items, [
                    'Total Department Payout',
                    $totalAmount
                ]);

                //spacing
                array_push($items, ['']);
            }

            array_push($items,[
                'Total All Department',
                $overAllTotal
            ]);
            
            $spreadSheetExport->setItems($items, 'Weekly Report_Department');
            $spreadSheetExport->setActiveWorkSheet('Weekly Report_Department');
            $spreadSheetExport->export();

        }

        private function exportPerUser($tsheetsGroupByUserGroupedByDays) {
            //prepare export
            $spreadSheetExport = new SpreadSheetExport('weekly_report');
            $exportHeaders = [
                'Name',
                'Rate',
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
                'Sun',
                'Total'
            ];

            $items = [];
            $rowCounter = 0;
            $totalAmountAllUser = 0;
            foreach($tsheetsGroupByUserGroupedByDays as $key => $user) {
                $timesheetDays = $user['timesheetByDays'];

                $addToItems = [];
                if(empty($user['fullname'])) continue;

                $totalAmount = 0;
                array_push($addToItems, $user['fullname']);
                array_push($addToItems, number_format($user['rate_per_day'],2));

                if(isset($timesheetDays['Mon'])) {
                   $extractedData = $this->extractTimesheet($timesheetDays['Mon']);
                   array_push($addToItems, $extractedData['stringResult']);
                   $totalAmount += $extractedData['totalAmount'];
                } else {
                    array_push($addToItems,'N/A');
                }

                if(isset($timesheetDays['Tue'])) {
                   $extractedData = $this->extractTimesheet($timesheetDays['Tue']);
                   array_push($addToItems, $extractedData['stringResult']);
                   $totalAmount += $extractedData['totalAmount'];
                } else {
                   array_push($addToItems,'N/A');
                }

                if(isset($timesheetDays['Wed'])) {
                    $extractedData = $this->extractTimesheet($timesheetDays['Wed']);
                    array_push($addToItems, $extractedData['stringResult']);
                    $totalAmount += $extractedData['totalAmount'];
                } else {
                    array_push($addToItems,'N/A');
                }

                if(isset($timesheetDays['Thu'])) {
                   $extractedData = $this->extractTimesheet($timesheetDays['Thu']);
                   array_push($addToItems, $extractedData['stringResult']);
                   $totalAmount += $extractedData['totalAmount'];
                } else {
                    array_push($addToItems,'N/A');
                }

                if(isset($timesheetDays['Fri'])) {
                   $extractedData = $this->extractTimesheet($timesheetDays['Fri']);
                   array_push($addToItems, $extractedData['stringResult']);
                   $totalAmount += $extractedData['totalAmount'];
                } else {
                   array_push($addToItems,'N/A');
                }

                if(isset($timesheetDays['Sat'])) {
                   $extractedData = $this->extractTimesheet($timesheetDays['Sat']);
                   array_push($addToItems, $extractedData['stringResult']);
                   $totalAmount += $extractedData['totalAmount'];
                } else {
                    array_push($addToItems,'N/A');
                }

                if(isset($timesheetDays['Sun'])) {
                    $extractedData = $this->extractTimesheet($timesheetDays['Sun']);
                    array_push($addToItems, $extractedData['stringResult']);
                    $totalAmount += $extractedData['totalAmount'];
                } else {
                    array_push($addToItems,'N/A');
                }


                array_push($addToItems, $totalAmount);

                array_push($items,$addToItems);
                $totalAmountAllUser += $totalAmount;

                $rowCounter++;
            }

            usort($items, function($a, $b) {
                return $a[0] <=> $b[0];
            });

            array_unshift($items, $exportHeaders);

            array_push($items,[
                'Total Payout',
                $totalAmountAllUser
            ]);

            $spreadSheetExport->setItems($items, 'Weekly Report');
            $spreadSheetExport->setActiveWorkSheet('Weekly Report');
            $spreadSheetExport->export();
        }

    }