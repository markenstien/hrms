<?php   

    class LoggedUsers extends Controller
    {

        public function __construct()
        {
            $this->timeLog = model('TimelogModel');
            $this->timelog_meta_model = model('TimelogMetaModel');
            $this->timesheetModel = model('TimesheetModel');
            $this->branch = model('BranchModel');
            authRequired();
        }

        public function index()
        {   
            $request = request()->inputs();
            $viewType = $request['view'] ?? 'clocked_in';
            $displayType = $request['branch_type'] ?? 'all';

            if(isEqual($viewType, 'clocked_in')) {
                if(empty($request['branch_type'])) {
                    $displayType = 'by_department';
                }
            }


            if((whoIs()['is_branch_timekeeper'] || isEqual(whoIs()['type'], 'admin')) != true) {
                return redirect('Dashboard');
            }

            if(isEqual($viewType , 'clocked_in')) {
                $loggedUsers = $this->timelog_meta_model->getClockedInUsers();
            }else{
                $loggedUsers = $this->timelog_meta_model->getClockedOutUsers();
            }
            
            $branches = $this->branch->all(null, 'branch asc');
            usort($branches, function($a, $b) {
                return $a->branch <=> $b->branch;
            });


            $groupedByBranch = [];
            foreach($branches as $branchIndex => $branchVal) {
                foreach($loggedUsers as $loggedIndex => $loggedVal) {
                    if($loggedVal->branch_id == $branchVal->id) {
                        if(!isset($groupedByBranch[$branchVal->id])) {
                            $groupedByBranch[$branchVal->id] = [
                                'name' => $branchVal->branch,
                                'users' => []
                            ];
                        }
                        $groupedByBranch[$branchVal->id]['users'][] = $loggedVal;
                    }
                }
            }
                

            if(isEqual($displayType, 'single_department')) {
                if(!isset($groupedByBranch[$request['branch_id']])) {
                    $groupedByBranch = [];
                } else {
                    $groupedByBranch = [$groupedByBranch[$request['branch_id']]];
                }
            }

            
            $data = [
                'title' => 'Logged Users',
                'loggedUsers' => $loggedUsers,
                'timeToday' => nowMilitary(),
                'viewType'   => $viewType,
                'displayType' => $displayType,
                'branches'   => arr_layout_keypair($branches,'id' , 'branch'),
                'groupedByBranch' => $groupedByBranch,
                'actionTxt' => isEqual($viewType,'clocked_in') ? 'Clock Out' : 'Clock In',
                'currentPageTxt' => isEqual($viewType,'clocked_in') ? 'Clocked In Users' : 'Clocked Out Users',
            ];
            return $this->view('logged_users/grouped', $data);
        }


        public function address()
        {
            $activeUsers = $this->timeLog->getActive();
            return $this->view('logged_users/address' , compact('activeUsers'));
        }

        public function active_staff_api()
        {
            $activeUsers = $this->timeLog->getActive();
            ee(api_response($activeUsers, true));
        }


        //get active users this is for java access for light data access
        public function active_staff_api_java()
        {
            $activeUsers = $this->timeLog->getActive_java();
            ee(api_response($activeUsers, true));
        }

        public function by_department()
        {
            $activeUsers = $this->timeLog->getActive();
            return $this->view('logged_users/department' , compact('activeUsers'));
        }

        public function xavierville()
        {
            $activeUsers = $this->timeLog->getActive();
            return $this->view('logged_users/9a' , compact('activeUsers'));
        }

        public function loginByDepartment($departmentId) {
            $users = $this->timelog_meta_model->getClockedOutUsers([
                'where' => [
                    'branch_id' => $departmentId
                ]
            ]);
            return $this->_loginUsers($users);
        }

        public function logoutAll() {
            $users = $this->timelog_meta_model->getClockedInUsers();
            return $this->_logoutUsers($users);
        }

        public function logoutByDepartment($departmentId) {
            $users = $this->timelog_meta_model->getClockedInUsers([
                'where' => [
                    'branch_id' => $departmentId
                ]
            ]);

            return $this->_logoutUsers($users);
        }

        private function _logoutUsers($users) {
             if($users) {
                foreach($users as $key => $row) {
                    $this->timelog_meta_model->log($row->user_id);
                }
                Flash::set("(".count($users).") users have been logged out.");
            } else {
                Flash::set("There are no users to log out");
            }

            return request()->return();
        }

        private function _loginUsers($users) {
            if($users) {
                foreach($users as $key => $row) {
                    $this->timelog_meta_model->log($row->id);
                }

                Flash::set("(".count($users).") users have been logged in.");
            } else {
                Flash::set("There are no users to log in");
            }

            return request()->return();
        }
    }