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
                    'username' => $post['username']
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
    }