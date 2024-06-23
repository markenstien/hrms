<?php 
    use Form\LeavePointForm;
    load(['LeavePointForm'], FORMS);

    class LeavePointController extends Controller
    {
        public $formLeavePoint;
        public $model, $userModel;

        public function __construct()
        {
            parent::__construct();
            $this->formLeavePoint = new LeavePointForm();
            $this->model = model('LeavePointModel');
            $this->userModel = model('UserModel');
        }

        public function index() {
            $condition = [];

            if(isEqual(whoIs('type'), 'REGULAR_EMPLOYEE')) {
                $condition = [
                    'lp.user_id' => whoIs('id')
                ];
            }
            $this->data['leave_point_logs'] = $this->model->getAll([
                'where' => $condition
            ]);
            return $this->view('leave_point/index', $this->data);
        }
        public function create() {

            if(isSubmitted()) {
                $post = request()->posts();

                if(empty($post['uid'])) {
                    Flash::set("Employee code must not be empty");
                    return request()->return();
                }
                $user = $this->userModel->get([
                    'user.uid' => $post['uid']
                ]);

                if(!$user) {
                    Flash::set("Employee with code '{$post['uid']}' not found, unable to process leave credits", 'danger');
                    return request()->return();
                }
                
                $post['user_id'] = $user->id;
                $this->model->addEntry($post);
                return redirect(_route('leave-point:index'));
            }
            $this->data['form'] = $this->formLeavePoint;
            return $this->view('leave_point/create', $this->data);
        }
    }