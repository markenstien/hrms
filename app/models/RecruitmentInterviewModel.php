<?php 

    class RecruitmentInterviewModel extends Model
    {
        public $table = 'recruitment_interviews';
        public $_fillables = [
            'recruitment_id',
            'interviewer_name',
            'interview_title',
            'interview_number',
            'remarks',
            'result',
        ];

        public function addNew($data) {
            $validColumns = parent::getFillablesOnly($data);
            $validColumns['interview_code'] = random_number(5);
            return parent::store($validColumns);
        }
    }