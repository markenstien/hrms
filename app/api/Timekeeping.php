<?php 



    class Timekeeping extends Controller

    {

        public function __construct()

        {

            $this->auth = Session::get('auth');

            $this->timeLog = model('TimelogModel');

            $this->loginToken = model('LoginTokenModel');

        }





        public function cx_clockIn()
        {   
            $tokenKey = $_POST['userToken'] ?? '';
            $user_id = $this->loginToken->getUserId($tokenKey);

            if($token) 
            {
                $result = $this->timeLog->clockIn($user_id);
                
                Flash::set('Successfully clocked in!');
                ee(api_response('Successfully clocked in!' , true));
            }else{
                ee(api_response('invalid token' , false));
            }
        }





        public function cx_clockOut()
        {
            $result = $this->timeLog->clockOut($this->auth['id']);
            Flash::set('Successfully clocked out!');
            if(!$result){
                Flash::set("Clockout failed");
                ee(api_response('Clockout failed' , false));
            }
            ee(api_response('Clockout Successfully' , true));
        }

    }