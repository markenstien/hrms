<?php 

    namespace Form;
    use Core\Form;
    use Module;

    load(['Form'], CORE);
    class RecruitmentInterviewForm extends Form
    {
        public function __construct()
        {
            parent::__construct();
            $this->addRecruitmentId();
            $this->addInterviewerName();
            $this->addRemarks();
            $this->addResult();
            $this->addInterviewTitle();
            $this->addInterviewNumber();
        }

        public function addRecruitmentId() {
            $this->add([
                'type' => 'hidden',
                'name' => 'recruitment_id',
                'required' => true
            ]);
        }

        public function addInterviewerName() {
            $this->add([
                'type' => 'text',
                'name' => 'interviewer_name',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Interviewer name'
                ]
            ]);
        }

        public function addRemarks() {
            $this->add([
                'type' => 'textarea',
                'name' => 'remarks',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Remarks'
                ]
            ]);
        }

        public function addResult() {
            $this->add([
                'type' => 'select',
                'name' => 'result',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Result',
                    'option_values' => Module::get('recruitment')['statusList']
                ]
            ]);
        }

        public function addInterviewTitle() {
            $this->add([
                'type' => 'text',
                'name' => 'interview_title',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Interview Title',
                ],
                'attributes' => [
                    'readonly' => true
                ]
            ]);
        }

        public function addInterviewNumber() {
            $this->add([
                'type' => 'text',
                'name' => 'interview_number',
                'class' => 'form-control',
                'required' => true,
                'options' => [
                    'label' => 'Interview Number',
                ],
                'attributes' => [
                    'readonly' => true
                ]
            ]);
        }
    }
