<?php 
    namespace Form;
    use Core\Form;

    load(['Form'], CORE);

    class ShiftForm extends Form {
        
        public function __construct()
        {
            parent::__construct();
            $this->addName();
            $this->addDescription();
        }

        public function addName() {
            $this->add([
                'type' => 'text',
                'name' => 'shift_name',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Shift Name'
                ]
            ]);
        }

        public function addDescription() {
            $this->add([
                'type' => 'textarea',
                'name' => 'shift_description',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Description'
                ]
            ]);
        }
    }