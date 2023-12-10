<?php 

    class EmployeeSalaryModel extends Model
    {
        public $table = 'employee_salary';
        public $_fillables = [
            'user_id',
            'salary_per_month',
            'salary_per_day',
            'salary_per_hour',
            'computation_type'
        ];

        public function addOrUpdate($employeeData, $id = null) {
            $validColumns = parent::getFillablesOnly($employeeData);

            if(parent::single([
                'user_id' => $employeeData['user_id']
            ])) {
                //update
                return parent::update($validColumns, [
                    'user_id' => $employeeData['user_id']
                ]);
            } else {
                //insert
                return parent::store($validColumns);
            }
        }
    }