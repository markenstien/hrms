<?php 

    namespace Form;
    use Core\Form;
    use Module;

    load(['Form'], CORE);
    class LeaveForm extends Form {
        private $userModel;
        public function __construct()
        {
            parent::__construct();

            if(!isset($this->userModel)) {
                $this->userModel = model('UserModel');
            }
            $this->addCategory();
            $this->addDateFiled();
            $this->addStartDate();
            $this->addEndDate();
            //not recommended
            $this->addRemarks();
            $this->addUser();
            $this->addStatus();

            $this->customSubmit('File Leave');
        }

        public function addDateFiled() {
            $this->add([
                'name' => 'date_filed',
                'type' => 'date',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Reference Date'
                ],
                'required' => true
            ]);
        }


        public function addStartDate() {
            $this->add([
                'name' => 'start_date',
                'type' => 'date',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Start Date'
                ],
                'required' => true
            ]);
        }


        public function addEndDate() {
            $this->add([
                'name' => 'end_date',
                'type' => 'date',
                'class' => 'form-control',
                'options' => [
                    'label' => 'End Date'
                ],
                'required' => true
            ]);
        }


        public function addCategory() {
            $this->add([
                'name' => 'leave_category',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Type of Leave',
                    'option_values' => Module::get('ee_leave')['categories']
                ],
                'required' => true
            ]);
        }

        public function addUser() {
            $users = $this->userModel->getAll([
                'order' => 'user.firstname asc'
            ]);

            $userArray = arr_layout_keypair($users, ['id', 'firstname@lastname']);
            
            $this->add([
                'name' => 'user_id',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => 'User',
                    'option_values' => $userArray
                ]
            ]);
        }

        public function addStatus() {
            $this->add([
                'name' => 'status',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Status',
                    'option_values' => Module::get('ee_leave')['status']
                ]
            ]);
        }

        public function addRemarks() {
            $this->add([
                'name' => 'remarks',
                'type' => 'select',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Remarks',
                    'option_values' => Module::get('ee_leave')['admin-approval-category']
                ]
            ]);
        }
    }