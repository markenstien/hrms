<?php 

    class Leave extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('LeaveModel');
        }

        /**
         * user_id
         * id
         */
        public function getLeave() {
            $inputs = request()->inputs();
            $condition = [];

            foreach($inputs as $key => $row) {
                if(!empty($row)) {
                    $condition['el.'.$key] = $row;
                }
            }

            if(empty($condition)) {
                echo json_encode("invalid query");
                return;
            }

            $response = $this->model->getAll([
                'where' => $condition
            ]);
            

            echo json_encode($response);
        }
    }