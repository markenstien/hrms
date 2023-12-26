<?php
    namespace Form;
    use Core\Form;
    load(['Form'], CORE);

    class HolidayForm extends Form{

        public function __construct()
        {
            parent::__construct();
            $this->addName();
            $this->addNameAbbr();
            $this->addDate();
            $this->addWorkType();
            $this->addPayType();
        }

        public function addName() {
            $this->add([
                'type' => 'text',
                'name' => 'holiday_name',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Holiday Name'
                ],
                'required' => true,
            ]);
        }

        public function addNameAbbr() {
            $this->add([
                'type' => 'text',
                'name' => 'holiday_name_abbr',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Holiday Name Abbr'
                ]
            ]);
        }

        public function addDate() {
            $this->add([
                'type' => 'date',
                'name' => 'holiday_date',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Date'
                ],
                'required' => true
            ]);
        }

        public function addWorkType() {
            $this->add([
                'type' => 'select',
                'name' => 'holiday_work_type',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Working Type',
                    'option_values' => [
                        'working' => 'Working Holiday',
                        'non_working' => 'Non Working Holiday'
                    ]
                ],
                'required' => true
            ]);
        }

        public function addPayType() {
            $this->add([
                'type' => 'select',
                'name' => 'holiday_pay_type',
                'class' => 'form-control',
                'options' => [
                    'label' => 'Pay Type',
                    'option_values' => [
                        'paid' => 'Paid Holiday',
                        'unpaid' => 'No Work No Pay Holiday'
                    ]
                ],
                'required' => true
            ]);
        }
    }