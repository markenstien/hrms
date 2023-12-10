<?php   

    class Admin extends Controller
    {

        public function index()
        {
            $data = [];
            return $this->view('dashboard/admin' , $data);
        }
    }