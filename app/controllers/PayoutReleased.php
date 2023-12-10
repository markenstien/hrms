<?php   

    class PayoutReleased extends Controller
    {
        public $table = 'wallets';

        public function __construct()
        {
            $this->model = model('PayoutModel');
        }

        public function groupByDate()
        {

            
        }
    }