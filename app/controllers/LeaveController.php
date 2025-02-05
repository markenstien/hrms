<?php

    use Form\LeaveForm;
    use Services\UserService;

    load(['LeaveForm'], FORMS);
    load(['UserService'], SERVICES);

    class LeaveController extends Controller
    {
        public $form;
        public $model,$userModel,
        $employmentModel,
        $leavePointModel;

        public function __construct()
        {
            parent::__construct();
            $this->form = new LeaveForm();
            $this->model = model('LeaveModel');
            $this->userModel = model('UserModel');
            $this->employmentModel = model('EmployeeModel');
            $this->leavePointModel = model('LeavePointModel');

            $this->data['form'] = $this->form;
            
        }

        public function index() {
            $req = request()->inputs();

            if(!empty($req['filter'])) {
                $validFilters = [
                    'user_id',
                    'leave_category',
                    'status',
                    'remarks'
                ];
                $condition = [];
                
                if(isEqual(whoIs('type'), UserService::REGULAR_EMPLOYEE)) {
                    $condition['el.user_id'] = whoIs('id');
                }
                foreach($req as $reqKey => $reqRow) {
                    if(isEqual($reqKey, $validFilters) && $reqRow != '') {
                        $condition["el.{$reqKey}"] = $reqRow;
                    }
                    
                }
                $leaves = $this->model->getAll([
                    'where' => $condition
                ]);
            } else {
                $condition = [];
                if(isEqual(whoIs('type'), UserService::REGULAR_EMPLOYEE)) {
                    $condition['el.user_id'] = whoIs('id');
                }
                $leaves = $this->model->getAll([
                    'where' => $condition,
                    'order' => 'FIELD(el.status, "pending", "approved", "declined"), el.updated_at desc',
                    'limit' => '30'
                ]);
            }
            $this->data['leaves'] = $leaves;
            $this->data['user'] = $this->userModel->get(whoIs('id'));
            return $this->view('leave/index', $this->data);
        }

        public function create() {
            if(isSubmitted()) {
                $post = request()->posts();
                $post['status'] = 'pending';
                $res = $this->model->add($post);

                if(!$res) {
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                } else {
                    Flash::set("Leave Request filed");
                    return redirect(_route('leave:index'));
                }
            }

            $this->data['leavePoint'] = $this->leavePointModel->getTotalByUser(whoIs('id'));
            //iniot user

            $this->data['form']->add([
                'type' => 'hidden',
                'value' => whoIs('id'),
                'name' => 'user_id'
            ]);
            return $this->view('leave/create', $this->data);
        }

        public function edit($id) {

            if(isSubmitted()) {
                $post = request()->posts();
                $this->model->updateWithValidation($post, $post['id']);

                Flash::set("Timesheet updated");
                return redirect(_route('leave:index'));
            }

            $leave = $this->model->get($id);

            $this->data['leave'] = $leave;
            $this->form->setValueObject($leave);
            $this->form->addId($leave->id);
            $this->form->setValue('submit', 'Save Changes');
            $this->data['form'] = $this->form;
            return $this->view('leave/edit', $this->data);
        }

        public function approve($id) {
            $this->model->approve($id);
            Flash::set("Leave Approved");
            return redirect(_route('leave:index'));
        }

        public function adminApproval($id) {
            if(isSubmitted()) {
                $post = request()->posts();
                $post['approved_by'] = whoIs('id');
                $post['approval_date'] = nowMilitary();
                $this->model->adminApproval($post);
                Flash::set("Leave Request updated");
                return redirect(_route('leave:index'));
            }
            $leave = $this->model->get($id);
            $this->data['leave'] = $leave;
            return $this->view('leave/admin_approval', $this->data);
        }

        public function show($id) {
            $leave = $this->model->get($id);
            $this->data['leave'] = $leave;
            return $this->view('leave/show', $this->data);
        }

        public function leaveSummary() {
            if(isEqual(whoIs('type'), UserService::REGULAR_EMPLOYEE)) {
                $users = $this->userModel->getAll([
                    'where' => [
                        'user.id' => whoIs('id')
                    ]
                ]);
            } else {
                $users = $this->userModel->getAll();
            }
            $summary = [];

            foreach($users as $key => $row) {
                $leaveReArranged = [];
                $summary[$row->id]['user'] = $row;

                $leavePoints = $this->leavePointModel->getTotalByUser($row->id); //format leave points

                if($leavePoints) {
                    foreach($leavePoints as $leaveKey => $leaveRow) {
                        $leaveReArranged[$leaveRow->leave_point_category] = $leaveRow->total_point;
                    }
                }

                $summary[$row->id]['leavePointSummary'] = $leaveReArranged;
            }


            $this->data['users'] = $users;
            $this->data['summary'] = $summary;

            return $this->view('leave/summary', $this->data);
        }

        public function userCredit($userId) {
            $this->data['user'] = $this->userModel->get($userId);
            $this->data['leaveCredits'] = $this->leavePointModel->getTotalByUser($userId);
            $this->data['leaveCreditTotal'] = $this->leavePointModel->getTotal($userId);
            return $this->view('leave/user_credit', $this->data);
        }
    }