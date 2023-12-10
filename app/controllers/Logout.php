<?php   

    class Logout extends Controller
    {

        public function index()
        {
            Session::remove('auth');

            return redirect('login');
        }
    }