<?php

    use Services\QRTokenService;
    load(['QRTokenService'],APPROOT.DS.'services');

    class Login extends Controller
    {

        public function __construct()
        {
            $this->user = model('UserModel');
        }

        
        public function loginQRToken() {
            $token = QRTokenService::getLatest(QRTokenService::LOGIN_TOKEN);
            $token->src_url = base64_decode($token->src_url);

            return $this->view('qrlogin/qr_token');
        }

        public function index()
        {
            if(Auth::get())
                return redirect('dashboard');
            return $this->view('login/index');
        }

        public function punchLogin()
        {
            $post = request()->posts();

            $username = trim($post['username']);
            $password = trim($post['password']);


            $user = $this->user->getByUsername($username);

            if($user)
            {
                if( isEqual($password , $user->password) ) 
                {
                    Flash::set("Welcome");
                    $auth = $this->user->startSession($user->id);
                }else{
                    Flash::set("Incorrect password" , 'danger');
                    return request()->return();
                }

                return redirect('Dashboard');
            }else{
                Flash::set("Not logged in " , 'danger');
                return redirect('login');
            }
        }


        // public function randomizeUsername()
        // {
        //     $db = Database::getInstance();

        //     $db->query("SELECT * FROM users");
        //     $users = $db->resultSet();

        //     foreach($users as $key => $row) 
        //     {
        //         $username = strtoupper(get_token_random_char(5));

        //         $db->query(
        //             "UPDATE users set username = '{$username}' ,
        //                 password = '12345'
        //                 where id = '$row->id' "
        //         );

        //         $db->update();
        //     }
        // }
    }