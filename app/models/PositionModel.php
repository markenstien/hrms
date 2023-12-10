<?php 

    class PositionModel extends Model
    {
        public $table = 'positions';
        public $_fillables = [
            'position_name',
            'min_rate',
            'max_rate'
        ];

        public function addNew($data) {
            $columns = $this->getFillablesOnly($data);
            $columns['position_code'] = strtoupper(substr($columns['position_name'],0,4).random_number(4));
            return parent::store($columns);
        }
    }