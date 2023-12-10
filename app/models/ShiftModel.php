<?php 

    class ShiftModel extends Model
    {
        public $table = 'shifts';
        public $_fillables = [
            'shift_name',
            'time_in',
            'time_out'
        ];
    }