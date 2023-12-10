<?php 
    class OvertimeController extends Controller
    {
        public function __construct()
        {
            $this->model = model('OvertimeLogModel');
            $this->userModel = model('UserModel');
            $this->branchModel = model('BranchModel');
            $this->userMetaModel = model('UserMetaModel');
            $this->systemLogModel = model('SystemLogModel');
        }

        public function create() {
            $udpates = [];
            if(isSubmitted()) 
            {
                $post = request()->posts();
                $log = $this->model->createLog($post['department_id'], $post['extra_time']);

                if(!$log) {
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                } else {
                    $users = $this->userModel->getAll([
                        'where' => [
                            'branch_id' => $post['department_id'],
                            'is_deleted' => false
                        ]
                    ]);

                    foreach($users as $key => $row) {
                        $user = $this->userMetaModel->getByUserid($row->id);
                        $maxWorkHours  = $user->max_work_hours + $post['extra_time'];

                        $isUpdated = $this->userMetaModel->update([
                            'max_work_hours' => $maxWorkHours
                        ],$user->id);
                    }   

                    Flash::set("OT Created");
                    return redirect("OvertimeController/index");
                }
            }
            $branches = $this->branchModel->all();
            $branchSelect = arr_layout_keypair($branches,['id', 'branch']);
                
            $data = [
                'title' => 'Create Overtime',
                'branchSelect' => $branchSelect,
                'logs' => $this->systemLogModel->all([
                    'log_category' => 'OVERTIME_LOGS'
                ])
            ];

            return $this->view('overtime/create', $data);
        }

        public function revert($id) {
            $overtimeLog = $this->model->get($id);
            if(isEqual($overtimeLog->status,'active')) {
                $users = $this->userModel->getAll([
                    'where' => [
                        'branch_id' => $overtimeLog->department_id,
                        'is_deleted' => false
                    ]
                ]);

                foreach($users as $key => $row) {
                    $user = $this->userMetaModel->getByUserid($row->id);
                    $isUpdated = $this->userMetaModel->update([
                        'max_work_hours' => $user->max_work_hours - $overtimeLog->extra_time
                    ], [
                        'user_id' => $user->id
                    ]);
                }   

                $response = $this->model->complete($id);

                if(!$response) {
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                } else {
                    Flash::set("OT Completed");
                    return redirect('overtimeController/index');
                }
            } else {
                Flash::set("OT already completed", 'danger');
                return redirect('user/index');
            }
            
        }

        public function index() {
            $overtimes = $this->model->getAll([
                'order' => 'overtime.id desc'
            ]);
            
            $data = [
                'overtimes' => $overtimes
            ];

            return $this->view('overtime/index', $data);
        }

        public function maxWorkHourReset() {

            if(isSubmitted()) {
                $post = request()->posts();

                //temporary to be deleted soon
                /**
                 * this code updates the user whre branch is
                 * equals the selected branch, this will updated the users max-hours
                 */

                if(isEqual($post['department_id'], 'all')) {
                    $users = $this->userModel->getAll([
                        'where' => [
                            'is_deleted' => false
                        ]
                    ]);
                } else {
                    $users = $this->userModel->getAll([
                        'where' => [
                            'branch_id' => $post['department_id'],
                            'is_deleted' => false
                        ]
                    ]);
                }

                $userIds = [];

                foreach($users as $key => $user) {
                    $userIds[] = $user->id;
                }

                if(empty($userIds)) {
                    Flash::set("There are no users to be updated", 'danger');
                    return request()->return();
                }
                $isOkay = $this->userMetaModel->update([
                    'max_work_hours' => $post['extra_time']
                ], [
                    'user_id' => [
                        'condition' => 'in',
                        'value' => $userIds
                    ]
                ]);

                if($isOkay) {
                    Flash::set("Department max work hours updated to {$post['extra_time']}");
                    return redirect('user');
                } else {
                    Flash::set("Unable to update users max hours", 'danger');
                    return request()->return();
                }
            }
            $branches = $this->branchModel->all();
            $branchSelect = arr_layout_keypair($branches,['id', 'branch']);
            $branchSelect['all'] = 'all';
            
            $data = [
                'title' => 'Reset Department Work Hours',
                'branchSelect' => $branchSelect
            ];
            return $this->view('overtime/max_hour_reset', $data);
        }

        public function delete($id) {
            $resp = $this->model->delete($id);
            if($resp) {
                Flash::set("Overtime Record Deleted");
                return request()->return();
            }else{
                Flash::set($this->model->getErrorString(), 'danger');
                return request()->return();
            }
        }
    }