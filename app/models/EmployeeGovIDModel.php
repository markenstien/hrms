<?php 

    class EmployeeGovIDModel extends Model
    {
        public $table = 'employee_gov_ids';
        public $_fillables = [
            'user_id',
            'id_type',
            'id_number',
            'is_verified'
        ];

        public function addOrUpdate($employeeData) {
            $_fillables = parent::getFillablesOnly($employeeData);

            if(parent::single([
                'user_id' => $employeeData['user_id'],
                'id_type' => $employeeData['id_type']
            ])) {
                return parent::update($_fillables, [
                    'user_id' => $_fillables['user_id'],
                    'id_type' => $_fillables['id_type']
                ]);
            } else {
                return parent::store($_fillables);
            }
        }
    }