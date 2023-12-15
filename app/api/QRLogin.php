<?php
    use Services\QRTokenService;
    load(['QRTokenService'], SERVICES);

    class QRLogin extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->timelogPlusModel = model('TimelogPlusModel');
            $this->userModel = model('UserModel');
        }
        public function renew() {
            $recentToken = QRTokenService::getLatest(QRTokenService::LOGIN_TOKEN);

            if(!$recentToken) {
                QRTokenService::renewOrCreate(QRTokenService::LOGIN_TOKEN);
            } else {
                $lastUpdated = strtotime($recentToken->updated_at);
                $timeToday = strtotime(nowMilitary());
                $hours = (($lastUpdated - $timeToday) / 60) /60;

                if($hours >= 12) {
                    QRTokenService::renewOrCreate(QRTokenService::LOGIN_TOKEN);
                    //update if changed
                    $recentToken = QRTokenService::getLatest(QRTokenService::LOGIN_TOKEN);
                }
            }

            echo json_encode([
                'data' => $recentToken
            ]);
        }

        public function log() {
            $req = request()->inputs();

            if(empty($req['userId'])) {
                if(isSubmitted()) {
                    $errors = '';
                    $post = request()->posts();
                    $user = $this->userModel->get([
                        'user.username' => $post['username']
                    ]);

                    if(!$user) {
                        $errors = "user not found";
                    } else {
                        if(!isEqual($user->password, $post['password'])) {
                            $errors = "invalid password";
                        }
                    }

                    if(!empty($errors)) {
                        $this->data['errors'] = $errors;
                        return $this->view('qr_login/log', $this->data);
                    }

                    $lastLog = $this->timelogPlusModel->getLastLog($user->id);
                    $typeOfAction = $this->timelogPlusModel->typeOfAction($lastLog);

                    $this->data['lastLog'] = $lastLog;
                    $this->data['typeOfAction'] = $typeOfAction;

                    $token = QRTokenService::getLatestToken(QRTokenService::LOGIN_TOKEN);
                    $this->data['actionURL'] = QRTokenService::getLink(QRTokenService::LOGIN_TOKEN,[
                        'token' => QRTokenService::LOGIN_TOKEN,
                        'device' => 'web',
                        'userId' => whoIs('id')
                    ]);

                    return $this->view('qr_login/log', $this->data);
                }
                return $this->view('qr_login/log', $this->data);
            }

            $token = QRTokenService::getLatestToken(QRTokenService::LOGIN_TOKEN);

            if(isEqual($token, $req['token'])) {
                $this->timelogPlusModel->log([
                    'userId' => $req['userId'],
                    'device' => 'web'
                ]);

                if(!empty($req['device']) && isEqual($req['device'], 'web')) {
                    Flash::set("welcome back, logged in using attendance qr");
                    $this->userModel->startSession($req['userId']);
                    return redirect(_route('dashboard:index'));
                }
    
                echo json_encode([
                    'success' => true,
                    'data' => $this->timelogPlusModel->getMessages()
                ]);
            } else{
                echo json_encode([
                    'success' => false,
                    'message' => 'QRCode Expired, unable to login'
                ]);
            }

            
        }

        public function getAction() {
            $req = request()->inputs();

            if(empty($req['userId'])) {
                _error("Invalid Request");
                return false;
            }
            
            $lastLog = $this->timelogPlusModel->getLastLog($req['userId']);
            $action = $this->timelogPlusModel->typeOfAction($lastLog);

            echo json_encode([
                'success' => true,
                'user_ud' => $req['userId'],
                'data' => $action
            ]);
        }
    }