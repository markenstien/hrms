<?php   

    class RememberMe extends Controller
    {
        public function index()
        {
            Cookie::set('auth'  , Session::get('auth'));

            Flash::set("Remeber Me Activated");

            return redirect('dashboard');
        }


        public function resetSession()
        {
            Session::set('auth' ,(array) Cookie::get('auth'));

            return redirect('dashboard');
        }
    }