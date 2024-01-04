<?php 

    class PayrollItem extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('PayrollItemModel');
        }

        /**
         * user_id
         * id
         */
        public function getPayslip() {
            $req = request()->inputs();
            $condition = [];
            foreach($req as $key => $row) {
                if(!empty($row)) {
                    $condition['item.'.$key] = $row;
                }
            }

            if(empty($condition)) {
                echo json_encode([
                    'data' => [],
                    'success' => false,
                    'message' => 'Invalid Query'
                ]);
                return;
            }

            $payslips = $this->model->getAll([
                'where' => $condition
            ]);

            echo json_encode([
                'data' => $payslips,
                'success' => true,
                'message' => 'List of Attendance'
            ]);
            
            return;
        }
    }