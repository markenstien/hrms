<?php

    use Services\QRTokenService;
    load(['QRTokenService'], APPROOT.DS.'services');
    class QRLoginController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->model = model('LoginSessionModel');
            $this->timelog_meta_model = model('TimelogMetaModel');
            $this->timeCardResponse = new TimeCardResponse();
        }

        public function viewImage() {
            // QRTokenService::renewOrCreate('LOGIN_TOKEN');
            $latestLoginImage = QRTokenService::getLatest('LOGIN_TOKEN');
            $this->data['lastestLoginImage'] = $latestLoginImage;
            return $this->view('qr_login/view_image', $this->data);
        }

        public function index()
        {
            if (!$this->model->checkSessionValidity()) {
                $this->model->endSession();
                echo "<center>";
                    "<div>" .Flash::get(). "</div>";
                echo die("SESSION_EXPIRED_RE_SCAN_QR_CODE");
                echo "</center>";
            }

            $user = $this->model->getUser();
            $data = [
                'user' => $user,
            ];

            if($user){
                $data['logLast'] = $this->timelog_meta_model->getLastLog($user->id);
                $data['logType'] = $this->timelog_meta_model->logType($data['logLast']);
                $data['timelogMeta'] = $this->timelog_meta_model;
            }

            return $this->view('qrlogin/index',$data);
        }

        /**
         * post request
         */
        public function login()
        {
            if (request()->is_post()) {
                $this->userModel = model('UserModel');
                $post = request()->posts();
                $user = $this->userModel->getByUsername($post['username']);

                if (!$user) {
                    Flash::set("User not found!");
                    return request()->return();
                }

                if (!isEqual($user->password,$post['password'])) {
                    Flash::set("Incorrect Password");
                    return request()->return();
                }
                
                $this->model->setUser($user);
                QRTokenService::renewOrCreate(QRTokenService::LOGIN_TOKEN);
                return redirect("QRLoginController");
            }
        }

        public function logTime()
        {
            if (request()->is_post()) 
            {
                $user = $this->model->getUser();
                $isActionOk = false;
                $todayMilitaryTime = todayMilitary();

                $this->timeCardResponse->setPunchDateTime(date_long($todayMilitaryTime, 'M d ,Y h:i:s a'))
                ->setPunchtime($todayMilitaryTime, 'h:i:s a')
                ->setPunchDate($todayMilitaryTime, 'M d ,Y');

                $response = $this->timelog_meta_model->log($user->id);

                $this->timeCardResponse->setUser($this->timelog_meta_model->getUser())
                ->setAction($this->timelog_meta_model->getAction());

                /**
                 * RESPONSE ISSUE
                 */
                if (!$response) {
                    $this->timeCardResponse->addMessage($this->timelog_meta_model->getErrorString());
                    Flash::set($this->timeCardResponse->getMessageStringFormat() , 'danger');
                    return request()->return();
                }
                /**
                 * NO ISSUE
                 */
                $action = $this->timelog_meta_model->getAction();

                if (isEqual($action, $this->timelog_meta_model::$CLOCKED_IN)) {
                    $this->timeCardResponse->addMessage($this->timelog_meta_model::$CLOCKED_IN);
                } else {
                    $convertHoursToMinutes = minutesToHours($this->timelog_meta_model->getValidWorkHours());
                    $this->timeCardResponse->addMessage($this->timelog_meta_model::$CLOCKED_OUT);
                    $this->timeCardResponse->addMessage("Total Time : {$convertHoursToMinutes}");
                }
                
                $isActionOk = true;

                if (isEqual($action, $this->timelog_meta_model::$CLOCKED_OUT)){
                    //unset all;
                    Flash::set("You have been loged out");
                    $this->model->logoutAll();
                }


                Flash::set($this->timeCardResponse->getMessageStringFormat());
                return request()->return();
            }
        }

        public function logoutAccount()
        {
            $this->model->logoutAll();
            return redirect('login');
        }
    }