<?php 
    
    class MonitoringController extends Controller
    {

        public function index()
        {
            $secret = seal('kamustakamahalkookaybasanadiparinnagbabago');
            
            // if(!isset($_GET['seal']) || !isEqual($_GET['seal'], $secret)) {
            //     _nte();
            //     return;
            // }
            

            $data = [
                'title' => 'Monitoring'
            ];

            return $this->view('monitoring/index', $data);
        }

        public function construction() 
        {
            $data = [
                'title' => 'Construction'
            ];

            return $this->view('monitoring/construction', $data); 
        }
    }