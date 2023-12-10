<?php 



    class Timekeeping extends Controller

    {

        public function __construct()

        {

            $this->auth = Session::get('auth');

            $this->timeLog = model('TimelogModel');

        }

        public function index()

        {



            // $timelogs = $this->timeLog->all([

            //     'user_id' => 1

            // ]);

            

            // $timeDifference = timeDifferenceInMinutes($timelogs[0]->punch_time , $timelogs[1]->punch_time);



            // dd($timeDifference);



            // die();

            $auth = Session::get('auth');



            return $this->view('timekeeping/index');

        }



        public function clockIn()
        {

            $result = $this->timeLog->clockIn($this->auth['id']);

            if(!$result) {
                Flash::set($this->timeLog->getErrorString() , 'danger');

                return request()->return();
            }

            Flash::set('Successfully clocked in!');



            return redirect('dashboard');

        }





        public function clockOut()
        {

            $result = $this->timeLog->clockOut($this->auth['id']);
            
            Flash::set('Successfully clocked out!');

            if(!$result){

                Flash::set( $this->timeLog->getErrorString() , 'danger');

            }

            return redirect('dashboard');

        }

    }