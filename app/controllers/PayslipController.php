<?php 

    class PayslipController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('PayrollItemModel');
        }

        public function index() {
            $this->data['payslips'] = $this->model->getAll([
                'where' => [
                    'item.user_id' => whoIs('id')
                ]
            ]);
            return $this->view('payslip/index', $this->data);
        }
    }