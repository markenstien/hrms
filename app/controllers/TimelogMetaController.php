<?php 

    class TimelogMetaController extends Controller
    {
        public function __construct()
        {
            if(empty(whoIs())) {
                Flash::set('Un-Authorized Action', 'danger');
                return redirect('login');
            }
            $this->timelog_meta_model = model('TimelogMetaModel');
            $this->userModel = model('UserModel');
        }

        public function log($userId)
        {
            $whoIs = whoIs();
            $dateTime = now();

            $res = $this->timelog_meta_model->log($userId);
            //will have data if log is called
            $action = $this->timelog_meta_model->getAction();
            $user = $this->userModel->single([
                'id' => $userId
            ]);
            if(!$res) {
                Flash::set($this->timelog_meta_model->getErrorString() , 'danger');
                $message = "
                    User {$user->firstname} {$user->lastname} has been tried to <strong>{$action}</strong> By user {$whoIs['firstname']} {$whoIs['lastname']} On {$dateTime},Action failed
                ";
                logger('ERR', trim($message), 'CLOCK_ACTION', $userId);
            }else{
                Flash::set($this->timelog_meta_model->getMessageString());
                $message = "
                    User {$user->firstname} {$user->lastname} has been <strong>{$action}</strong> By user {$whoIs['firstname']} {$whoIs['lastname']} On {$dateTime}
                ";
                logger('ERROR', trim($message), 'CLOCK_ACTION', $userId);
            }

            if(!empty(request()->input('redirect'))) {
                return request()->return();
            }

            return redirect('loggedUsers');
        }
    }