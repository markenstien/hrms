<?php

use PhpOffice\PhpSpreadsheet\Shared\Date;

    class LoginSessionModel extends Model
    {
        public $table = 'login_sessions';


        public function checkSessionValidity()
        {
            $token = Session::get('login_token');
            $lastSession = parent::last();

            if($lastSession->session_key != $token) {
                return false;
            }

            // $session = parent::single([
            //     'session_key' => $token
            // ]);

            // if(!$session){
            //     return false;
            // }

            return true;
        }

        public function start()
        {
            $token = get_token_random_char(15);
            Session::set('login_token' , $token);
            return parent::store([
                'session_key' => $token,
                'created_at' => today()
            ]);
        }

        public function token()
        {
            return Session::get('login_token');
        }

        public function endSession()
        {
            Session::remove('login_token');
        }


        public function getUser()
        {
            return Session::get('login_session_auth');
        }
        public function setUser($user)
        {
            Session::set('login_session_auth' , $user);
            return $user;
        }

        public function logoutUser()
        {
            Session::remove('login_session_auth');
        }

        public function logoutAll()
        {
            $this->logoutUser();
            $this->endSession();
        }
    }