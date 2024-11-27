<?php
    use Form\UserForm;
    load(['UserForm'], FORMS);

    class UserController extends Controller
    {
        public $form, $recruitmentModel;

        public function __construct()
        {
            parent::__construct();
            $this->user = model('UserModel');
            $this->branch = model('BranchModel');
            $this->schedule = model('ScheduleModel');
            $this->logModel = model('SystemLogModel');
            $this->payrollItemModel = model('PayrollItemModel');
            $this->recruitmentModel = model('RecruitmentModel');

            $this->form = new UserForm();

            $this->data['form'] = $this->form;
            authRequired();
        }



        public function index()
        {
            $branches  = $this->branch->all();
            $users = $this->user->getAll([
                'order' => 'user.id desc'
            ]);
            $this->data['users'] = $users;
            $this->data['branches'] = arr_layout_keypair($branches, ['id','branch']);

            return $this->view('user/index' , $this->data);
        }

        public function create()
        {
            $req = request()->inputs();

            if(isSubmitted()) {
                $post = request()->posts();
                $response = $this->user->addNew($post);

                if($response) {
                    $this->user->uploadProfile('profile', $this->user->_getRetval('userId'));

                    if(!empty($post['recruitment_id'])) {
                        $this->recruitmentModel->onboard($post['recruitment_id'], whoIs('id'));
                    }
                    Flash::set($this->user->getMessageString());
                    return redirect(_route('user:show', $this->user->_getRetval('userId')));
                } else {
                    Flash::set($this->user->getErrorString(), 'danger');
                    return request()->return();
                }
            }
            
            if(!empty($req['recruitmentId'])) {
                $recruitmentId = unseal($req['recruitmentId']);
                $recruitmentData = $this->recruitmentModel->get($recruitmentId);

                if($recruitmentData){
                    //auto fill user data
                    $this->form->setValue('firstname', $recruitmentData->firstname);
                    $this->form->setValue('lastname', $recruitmentData->firstname);
                    $this->form->setValue('email', $recruitmentData->email);
                    $this->form->setValue('mobile_number', $recruitmentData->mobile_number);
                    $this->form->setValue('address', $recruitmentData->address);
                    $this->form->setValue('position_id', $recruitmentData->position_id);

                    $this->data['recruitmentId'] = $recruitmentId;
                }
            }
            $branches = $this->branch->all(null , ' branch asc ');
            $this->data['branches'] = arr_layout_keypair($branches , ['id' , 'branch']);
            return $this->view('user/create' , $this->data);
        }

        /**
         * for staff accounts only
         * HR,PAYROL,ADMIN
         */
        public function createManagementAccount() {
            return $this->view('user/create_management_account', $this->data);
        }

        public function edit($id)
        {
            if(isSubmitted()) {
                $post = request()->posts();
                $isUpdateOkay = $this->user->updateComplete($post, $post['id']);
                $user = $this->user->get($id);

                if(!$isUpdateOkay) {
                    Flash::set($this->user->getErrorString(), 'danger');
                    return request()->return();
                }

                //try to upload image
                if(!upload_empty('profile')) {
                    $isUploadOk = $this->user->uploadProfile('profile', $id);
                    if(!$isUploadOk) {
                        Flash::set($this->user->getErrorString(), 'danger');
                        return request()->return();
                    }
                }

                if(isEqual(whoIs('id'), $id)) {
                    $this->user->startSession($id);
                }

                Flash::set("User has been updated");
                return redirect(_route('user:show', $id));
            }

            $branches = $this->branch->all(null , ' branch asc ');
            $user = $this->user->get($id);
            
            $data = [
                'user' => $user,
                'scheduleToday' => $this->schedule->getToday($id),
                'branches' => arr_layout_keypair($branches, ['id' , 'branch']),
                'logs' => $this->logModel->all([
                    'user_id' => $id,
                    'log_category' => 'USER_CHANGE_INFO'
                ], 'id desc')
            ];

            $this->form->setValueObject($user);
            $this->form->addId($id);
            $data['form'] = $this->form;

            $data = array_merge($data, $this->data);
            
            return $this->view('user/edit', $data);
        }

        public function editCredentials($userId) {
            
            if(isSubmitted()) {
                $post = request()->posts();

                switch($post['action_type']) {
                    case 'change_username':
                        $res = $this->user->changeUsername($post['user_id'], $post['username'], $post['password']);
                        if($res) {
                            Flash::set("Username updated");
                        } else {
                            Flash::set($this->user->getErrorString(), 'danger');
                        }
                    break;
                    case 'change_password':
                        $res = $this->user->changePassword($post['user_id'], $post['new_password']);
                        if($res) {
                            Flash::set("Password updated");
                        } else {
                            Flash::set($this->user->getErrorString(), 'danger');
                        }
                    break;
                }

                return redirect(_route('user:show', $userId));
            }
            $this->data['user'] = $this->user->get($userId);
            
            return $this->view('user/edit_credentials', $this->data);
        }

        public function profile() {
            return $this->show(whoIs('id'));
        }

        public function show($id) {
            $this->data['user'] = $this->user->get($id);
            $this->data['payslips'] = $this->payrollItemModel->getAll([
                'where' => [
                    'item.user_id' => $id
                ]
            ]);
            
            $this->data['files'] = $this->_attachmentModel->all([
                'global_key' => 'user_resources',
                'global_id' => $id
            ]);

            $this->data['userId'] = $id;
            $this->data['attachmentForm'] = $this->_attachmentForm;
            return $this->view('user/show', $this->data);
        }

        public function update_work_time()
        {
            $post = request()->posts();
            $result = $this->user->update_work_time($post);

            if(!$result)
            {
                Flash::set("Error Updating Work Time");
                return request()->return();
            }
            Flash::set("Updated Succesfully");
            return request()->return();
        }

        public function delete($userId)
        {
            $res = $this->user->delete($userId);

            if(!$res) {
                Flash::set($this->user->getErrorString(), 'danger');
            } else {
                Flash::set($this->user->getMessageString());
            }
            return request()->return();
        }

        /**
         * remove user from database
         */
        public function destroy($userId)
        {
            $res = $this->user->destroy($userId);

            if(!$res) {
                Flash::set($this->user->getErrorString(), 'danger');
            } else {
                Flash::set($this->user->getMessageString());
            }

            return redirect('user/index');
        }


        public function recover($userId)
        {
            $this->user->update([
                'is_deleted' => false
            ], $userId);

            Flash::set("Account recovered");

            return request()->return();
        }

        public function rollback_work_time()
        {   
            $post = request()->posts();
            $result = $this->user->rollback_work_time($post);

            if(!$result)
            {
                Flash::set("Error Rollback of Work Time");
                return request()->return();
            }
            Flash::set("Rollback succesfully");
            return request()->return();
              
        }

        public function department_timekeeper() {
            $req = request()->inputs();

            if(!empty($req['action'])) {
                if($req['action'] == 'remove') {
                    $this->user->updateBranchTimekeeper(false, $req['user_id']);                    
                }
            }
            
            $data = [
                'users' => $this->user->getAll([
                    'where' => [
                        'is_branch_timekeeper' => true
                    ]
                ])
            ];
            return $this->view('user/department_timekeeper', $data);
        }

        public function rollback_all_work_time()
        {      
           /*$result = $this->user->rollback_all_work_time();

           $smsMessage = "Ok Work Hour and Max Work Reset";

           if(!$result)
           {
             $smsMessage = "Error Work Hour and Max Work Reset";
           }

           sendSMS('09478884834' , $smsMessage);*/
        }
    }