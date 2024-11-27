<?php
    namespace Form;
    use Core\Form;

    load(['Form'], CORE);

    class AttendanceForm extends Form {

        public function __construct()
        {
            parent::__construct();
            
            $this->addEntryType();
            $this->addStartDate();
            $this->addEndDate();
            $this->addStartTime();
            $this->addEndTime();
            $this->addReason();
            $this->addUID();
            $this->addUserId();
        }

        public function addEntryType() {
            $this->add([
                'type' => 'select',
                'name' => 'entry_type',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Entry Type',
                    'option_values' => [
                        'SHIFT_REGULAR'  => 'REGULAR SHIFT',
                        'OT_REGULAR'     => 'OVERTIME'
                    ]
                ]
            ]);
        }

        public function addStartDate() {
            $this->add([
                'type' => 'date',
                'name' => 'start_date',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Start Date',
                ]
            ]);
        }

        public function addEndDate() {
            $this->add([
                'type' => 'date',
                'name' => 'end_date',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'End Date',
                ]
            ]);
        }

        public function addStartTime() {
            $this->add([
                'type' => 'text',
                'name' => 'time_in',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Start Time',
                    'placeholder' => '24 hour format'
                ]
            ]);
        }

        public function addEndTime() {
            $this->add([
                'type' => 'text',
                'name' => 'time_out',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'End Time',
                    'placeholder' => '24 hour format'
                ]
            ]);
        }

        public function addReason() {
            $this->add([
                'type' => 'text',
                'name' => 'reason',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Reason',
                ]
            ]);
        }

        public function addUserId() {
            $this->add([
                'type' => 'hidden',
                'name' => 'user_id',
            ]);
        }

        public function addUsername() {
            $this->add([
                'type' => 'text',
                'name' => 'username',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Username',
                    'placeholder' => 'Add username if filling up attendance for someone else'
                ]
            ]);
        }

        public function addUID() {
            $this->add([
                'type' => 'text',
                'name' => 'uid',
                'class' => 'form-control',
                'options' => [
                    'label' => 'User ID',
                ],

                'attributes' => [
                    'placeholder' => 'Add User ID if filling up attendance for someone else leave empty if for you'
                ]
            ]);
        }
    }