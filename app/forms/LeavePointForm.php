<?php
    namespace Form;
    use Core\Form;
    use Module;
    
    load(['Form'], CORE);

    class LeavePointForm extends Form {

        private $userModel;

        public function __construct()
        {
            parent::__construct();

            if(!isset($this->userModel)) {
                $this->userModel = model('UserModel');
            }

            $this->addUser();
            $this->addUserCode();
            
            $this->addType();
            $this->addPoint();
            $this->addPointType();
            $this->addRemarks();
        }

        public function addUserCode() {
            $this->add([
                'name' => 'uid',
                'type' => 'text',
                'required' => true,
                'options' => [
                    'label' => 'Employee Code',
                ],
                'class' => 'form-control'
            ]);
        }
        public function addUser() {
            $users = $this->userModel->getAll();
            $usersArray = arr_layout_keypair($users,['id','firstname@lastname@uid']);
            $this->add([
                'name' => 'user_id',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'label' => 'Employee',
                    'option_values' => $usersArray
                ],
                'class' => 'form-control'
            ]);
        }

        public function addType() {
            $this->add([
                'name' => 'leave_point_category',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'label' => 'Type of Leave',
                    'option_values' => Module::get('ee_leave')['categories']
                ],
                'class' => 'form-control'
            ]);
        }

        public function addPoint() {
            $this->add([
                'name' => 'point',
                'type' => 'text',
                'required' => true,
                'options' => [
                    'label' => 'Point'
                ],
                'class' => 'form-control'
            ]);
        }

        public function addPointType() {
            $this->add([
                'name' => 'point_type',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'label' => 'Point Entry',
                    'option_values' => [
                        'deduct' => 'Deduct',
                        'add' => 'Add'
                    ]
                ],
                'class' => 'form-control'
            ]);
        }

        public function addRemarks() {
            $this->add([
                'name' => 'remarks',
                'type' => 'textarea',
                'required' => true,
                'options' => [
                    'label' => 'Remarks'
                ],
                'class' => 'form-control',
                'attributes' => [
                    'rows' => 5
                ]
            ]);
        }
    }