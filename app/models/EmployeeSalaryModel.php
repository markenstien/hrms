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

            if(!empty($validColumns['salary_per_month'])) {
                $validColumns['salary_per_month'] = str_to_currency_only($validColumns['salary_per_month']);
            }

            if(!empty($validColumns['salary_per_month'])) {
                $validColumns['salary_per_month'] = str_to_currency_only($validColumns['salary_per_month']);
            }

            if(!empty($validColumns['salary_per_day'])) {
                $validColumns['salary_per_day'] = str_to_currency_only($validColumns['salary_per_day']);
            }

            if(!empty($validColumns['salary_per_hour'])) {
                $validColumns['salary_per_hour'] = str_to_currency_only($validColumns['salary_per_hour']);
            }

            if(!is_null($id)) {
                $record = parent::single($id);

                if(!$record) {
                    return parent::store($validColumns);
                } else {
                    return parent::update($validColumns, $id);
                }
            } else {
                return parent::store($validColumns);
            }
        }
    }