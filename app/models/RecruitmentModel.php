<?php 

    class RecruitmentModel extends Model
    {
        public $table = 'recruitments';
        public $_fillables = [
            'firstname',
            'lastname',
            'email',
            'mobile_number',
            'address',
            'position_id',
            'expected_salary',
            'remarks',
            'result',
            'created_by',
            'created_at',
        ];

        public function addNew($recruitmentData) {
            $validColumns = parent::getFillablesOnly($recruitmentData);
            //check for validations
            $candidateId = parent::store($validColumns);

            if(!$candidateId) {
                $this->addError("Unable to add candidate, save failed");
                return false;
            }
            //for readability
            parent::_addRetval('candidateId', $candidateId);
            
            return $candidateId;
        }
    }