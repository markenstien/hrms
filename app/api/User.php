<?php   
    class User extends Controller

    {   

        public function __construct()
        {

            $this->userMeta = model('UserMetaModel');

            $this->user = model('UserModel');

            $this->loginToken = model('LoginTokenModel');

        }

        public function index()
        {

            $users = $this->userMeta->getComplete();
            if(!$users)
            {
                ee(api_response($users , false));
            }else{
                ee(api_response($users));
            }
        }



        public function login()

        {



        }

        

        public function get()

        {

            $token = $_GET['token'] ?? '';



            if(empty($token))

            {

                ee(api_response('Invalid Request' , false));

                return false;

            }

            

            $user = $this->userMeta->getByToken($token);



            if($user){

                ee(api_response($user));

            }else{

                ee(api_response('no user found' , false));

            }

            

        }



        public function getComplete()

        {

            $token = $_GET['token'] ?? '';



            if(empty($token))

            {

                ee(api_response('Invalid Request' , false));

                return false;

            }

            $user = $this->userMeta->getByTokenComplete($token);



            if($user){

                ee(api_response($user));

            }else{

                ee(api_response('no user found' , false));

            }

        }



        public function update()

        {

            $post = request()->inputs();



            $updateUser = $this->userMeta->updateByToken([

                'rate_per_hour' => $post['ratePerHour'],

                'rate_per_day'  => $post['ratePerDay'],

                'work_hours'    => $post['workHours'],

                'max_work_hours' => $post['maxWorkHours'],

                'bk_username'   => $post['username']

            ], $post['userToken']);

            

            if($updateUser){

                ee(api_response('User updated!'));

            }else{

                ee(api_response('Something went wrong' , false));

            }

        }



        public function register()

        {

            $post = request()->inputs();



            $user = $this->user->getByApi($post['domain'] , $post['userToken']);



            if(!$user) 

            {

                $user = [

                    'firstname' => $post['firstname'],

                    'lastname'  => $post['lastname']

                ];

                

                $userMeta = [

                    'domain'    => $post['domain'],

                    'domain_user_token' => $post['userToken'],

                    'rate_per_hour'  => $post['ratePerHour'],

                    'rate_per_day'   => $post['ratePerDay'],

                    'work_hours'     => $post['workHours'],

                    'max_work_hours' => $post['maxWorkHours'],

                    'bk_username'       => $post['username']

                ];





                $registration = $this->user->apiRegister($user , $userMeta);



                $user = $this->user->getByApi($post['domain'] , $post['userToken']);



                $loginToken =  $this->loginToken->save($user->id);



                $data = [

                    'message' => 'Logged in successful',

                    'token'   => $loginToken

                ];



                ee(api_response( $data ));

            }else

            {

                ee(api_respose('already registered' , false));

            }

        }





        public function delete()

        {

            $token = request()->input('userToken');



            $res = $this->user->deleteByToken($token);

            

            if($res) {

                ee(api_response('User Deleted'));

            }else{

                ee(api_response('Something went wrong') , false);

            }

        }

    }