<?php   

    class Auth
    {
        public static $PREFIX = 'AUTH';


        public function set($data)
        {
            Session::set('auth' , $data);
        }

        public static function get(){
            
            return Session::get('auth');
        }

        public static function stop()
        {
            Session::remove('auth');
        }
    }