<?php   

    class Camera extends Controller
    {
        public function index()
        {
            return $this->view('camera/index');
        }
    }