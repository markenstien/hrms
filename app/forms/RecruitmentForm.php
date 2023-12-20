<?php 

    namespace Form;
    use Core\Form;
use Module;

    load(['Form'], CORE);

    class RecruitmentForm extends Form {
        private $positionModel;
        public function __construct()
        {
            parent::__construct();
            $this->positionModel = model('PositionModel');

            $this->addFirstName();
            $this->addLastName();
            $this->addEmail();
            $this->addMobileNumber();
            $this->addAddress();
            $this->addRemarks();
            $this->addPosition();
            $this->addExpectedSalary();
            $this->addResult();
        }

        public function addFirstName() {
            $this->add([
                'type' => 'text',
                'name' => 'firstname',
                'class' => 'form-control',
                'options' => [
                    'label' => 'First Name'
                ],
                'required' => true
            ]);
        }

        public function addLastName() {
            $this->add([
                'type' => 'text',
                'name' => 'lastname',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Last Name'
                ],
                'required' => true
            ]);
        }

        public function addEmail() {
            $this->add([
                'type' => 'email',
                'name' => 'email',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Email'
                ],
                'required' => true
            ]);
        }

        public function addMobileNumber() {
            $this->add([
                'type' => 'text',
                'name' => 'mobile_number',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Mobile Number'
                ],
                'required' => true
            ]);
        }

        public function addAddress() {
            $this->add([
                'type' => 'textarea',
                'name' => 'address',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Address'
                ],
                'required' => true
            ]);
        }

        public function addRemarks() {
            $this->add([
                'type' => 'textarea',
                'name' => 'remarks',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Remarks'
                ],
                'required' => true
            ]);
        }

        public function addPosition() {
            $positions = $this->positionModel->getAll();
            $positionArray = arr_layout_keypair($positions, ['id', 'position_name']);
            
            $this->add([
                'type' => 'select',
                'name' => 'position_id',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Position',
                    'option_values' => $positionArray
                ],
                'required' => true
            ]);
        }

        public function addExpectedSalary() {
            $this->add([
                'type' => 'text',
                'name' => 'expected_salary',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Expected Salary'
                ],
                'required' => true
            ]);
        }
        

        public function addResult() {
            $this->add([
                'type' => 'select',
                'name' => 'result',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Result',
                    'option_values' => Module::get('recruitment')['statusList']
                ]
            ]);
        }
    }