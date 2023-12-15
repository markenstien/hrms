<?php
    use Services\QRTokenService;
    load(['QRTokenService'], SERVICES);

    class QRLogin extends Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->timelogPlusModel = model('TimelogPlusModel');
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
                _error("Invalid Request");
                return false;
            }

            $token = QRTokenService::getLatestToken(QRTokenService::LOGIN_TOKEN);

            if(isEqual($token, $req['token'])) {
                $this->timelogPlusModel->log([
                    'userId' => $req['userId'],
                    'device' => 'web'
                ]);
    
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