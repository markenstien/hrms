<?php 

    class LeaveController extends Controller
    {
        public function index() {
            return $this->view('leave/index', $this->data);
        }

        public function create() {

        }

    }