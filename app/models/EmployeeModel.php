<?php 

    class EmployeeModel extends Model
    {
        public $table = 'employee_datas';
        public $_fillables = [
            'user_id',
            'hire_date',
            'shift_id',
            'position_id',
            'department_id'
        ];

        public function addOrUpdate($employeeData) {
            $employeeValidColumns = parent::getFillablesOnly($employeeData);

            if($user = parent::single([
                'user_id' => $employeeData['user_id']
            ])) {
                //update
                return parent::update($employeeValidColumns, [
                    'user_id' => $employeeData['user_id']
                ]);
            } else {
                //add new 
                return parent::store($employeeValidColumns);
            }
        }
    }