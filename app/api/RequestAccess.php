<?php   

    class RequestAccess extends Controller
    {

        public function __construct()
        {
            $this->user = model('UserModel');

            $this->loginToken = model('LoginTokenModel');
        }

        public function index()
        {
            $post = request()->inputs();

            if(empty($post))
            {
                ee( api_response('Invalid passed parameters' , false) );
            }else
            {
                $user = $this->user->getByApi($post['domain'] , $post['userToken']);
                
                if(!$user) {
                    ee(api_response('You have no account with us' , false));
                }else{
                    $loginToken =  $this->loginToken->save($user->id);
    
                    $data = [
                        'message' => 'Logged in successful',
                        'token'   => $loginToken
                    ];

                    ee(api_response( $data ));
                }
            }
        }

        public function login()
        {
            $tokenKey = $_GET['token'] ?? '';
            
            $token = $this->loginToken->getAndUpate($tokenKey);

            if($token) 
            {
                Session::set('refferer' , request()->referrer());

                $this->user->startSession($token->user_id);

                Flash::set("Account logged in");
                return redirect('dashboard');
            }else{
                ee(api_response('invalid token' , false));
            }
        }


        public function logout()
        {   
            $auth = Session::get('auth');
            $endpoint = 'https://breakthrough-e.com';

            // $endpoint = 'http://dev.breakthrough';

            Session::remove('auth');
            $referrer = Session::get('referrer') ?? '';

            if(empty($referrer)){
                header("Location:{$endpoint}/API_TKAPP/relogin?domain_user_token={$auth['apiToken']}");
            }else{
                header("Location:{$referrer}");
            }
            
        }
    }