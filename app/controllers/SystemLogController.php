<?php 

    class SystemLogController extends Controller
    {
        public function __construct()
        {
            $this->logModel = model('SystemLogModel');
        }

        public function index() {
            $data = [
                'logs' => $this->logModel->getAll([
                    'order' => 'sl.id desc'
                ])
            ];

            return $this->view('system_log/index', $data);
        }
    }