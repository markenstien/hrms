<?php 

    class User extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('UserModel');
        }

        public function authenticate() {
            $retVal = [];
            $req = request()->inputs();

            if(isSubmitted()) {
                $post = request()->posts();
                $user = $this->model->get([
                    'email' => $post['email']
                ]);
    
                if(!$user) {
                    $retVal['message'] = " user not found";
                    $retVal['success'] = false;
                } elseif(!isEqual($user->password, $post['password'])) {
                    $retVal['message'] = " Incorrect Password ";
                    $retVal['success'] = false;
                } else {
                    $retVal['message'] = 'user authenticated';
                    $retVal['success'] = true;
                    $retVal['user'] = $user;
                }
    
                echo json_encode($retVal);
            }
        }

        public function getById() {
            $retVal = [];
            $req = request()->inputs();
            $user = $this->model->get($req['id']);

            if(!$user) {
                $retVal['message'] = 'No user found';
            } else {
                $retVal['message'] = 'User found';
                $retVal['user'] = $user;
            }
            
            echo json_encode($retVal);
        }
    }