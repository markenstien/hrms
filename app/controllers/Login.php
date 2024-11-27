<?php

    use Services\QRTokenService;
    load(['QRTokenService'],APPROOT.DS.'services');

    class Login extends Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->user = model('UserModel');
        }

        
        public function loginQRToken() {
            $token = QRTokenService::getLatest(QRTokenService::LOGIN_TOKEN);
            $token->src_url = base64_decode($token->src_url);

            return $this->view('qrlogin/qr_token');
        }

        public function index()
        {
            $req = request()->inputs();

            $token = QRTokenService::getLatest(QRTokenService::LOGIN_TOKEN);

            $this->data['token'] = $token;
            $this->data['showToken'] = false;

            if(Auth::get())
                return redirect('dashboard', $this->data);

            if(!empty($req['token']) && isEqual($req['token'], '1236674068')){
                $this->data['showToken'] = true;
            }

            return $this->view('login/index', $this->data);
        }

        public function punchLogin()
        {
            $post = request()->posts();
            $user = $this->user->get([
                'email' => trim($post['email'])
            ]);

            $password = trim($post['password']);
            
            if($user)
            {
                if(isEqual($password , $user->password) ) 
                {
                    Flash::set("Welcome");
                    $auth = $this->user->startSession($user->id);
                }else{
                    Flash::set("Incorrect password" , 'danger');
                    return request()->return();
                }
                
                // if(isEqual($post['email'],'admin@korpee.app')) {
                //     Flash::set("Welcome");
                //     $auth = $this->user->startSession($user->id);
                // } else {
                //     if(isEqual($password , $user->password) ) 
                //     {
                //         Flash::set("Welcome");
                //         $auth = $this->user->startSession($user->id);
                //     }else{
                //         Flash::set("Incorrect password" , 'danger');
                //         return request()->return();
                //     }
                // }
                
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