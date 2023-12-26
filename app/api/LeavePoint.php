<?php 

    class LeavePoint extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('LeavePointModel');
        }

        public function getTotalByUser() {
            $inputs = request()->inputs();

            if(empty($inputs)) {
                echo json_encode("Invalid Query");
                return;
            }
            
            $response = $this->model->getTotalByUser($inputs['user_id']);
            echo json_encode($response);
        }
    }