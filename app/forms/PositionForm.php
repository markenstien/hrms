<?php
namespace Form;
use Core\Form;
load(['Form'], CORE);
class PositionForm extends Form {

    public function __construct()
    {
        parent::__construct();
        $this->init();
        $this->addName();   
        $this->addMinRate();   
        $this->addMaxRate();   
    }

    public function addName() {
        $this->add([
            'name' => 'position_name',
            'type' => 'text',
            'id' => 'position_name',
            'class' => 'form-control',
            'required' => true,
            'options' => [
                'label' => 'Position Name'
            ]
        ]);
    }

    public function addMinRate() {
        $this->add([
            'name' => 'min_rate',
            'type' => 'text',
            'id' => 'min_rate',
            'class' => 'form-control',
            'required' => true,
            'options' => [
                'label' => 'Minimum Salary'
            ]
        ]);
    }

    public function addMaxRate() {
        $this->add([
            'name' => 'max_rate',
            'type' => 'text',
            'id' => 'max_rate',
            'class' => 'form-control',
            'required' => true,
            'options' => [
                'label' => 'Max Salary'
            ]
        ]);
    }
}