<?php
    namespace Services;
    use Database;
    use QRcode;

    class QRTokenService {

        const RENEW_ACTION  = 'RENEW_ACTION';
        const CREATE_ACTION = 'CREATE_ACTION';
        const LOGIN_TOKEN = 'LOGIN_TOKEN';

        public static function getLatestToken($category) {
            $db = Database::getInstance();
            $db->query(
                "SELECT * FROM qr_tokens
                    WHERE category = '$category'
                    ORDER BY id desc"
            );
            return $db->single()->token ?? false;
        }

        public static function getLatest($category) {
            $db = Database::getInstance();
            $db->query(
                "SELECT * FROM qr_tokens
                    WHERE category = '$category'
                    ORDER BY id desc"
            );
            return $db->single() ?? false;
        }

        public static function getLink($category, $params = []) {
            if(!empty($params)) {
                $paramsString = keypairtostr($params,'=','&','',true);
                return URL.'/api/QRLogin/log?'.$paramsString;
            }
            return URL.'/api/QRLogin/log';
        }

        public static function renewOrCreate($category) {
            require_once LIBS.DS.'phpqrcode'.DS.'qrlib.php';
            $db = Database::getInstance();

            $db->query(
                "SELECT * FROM qr_tokens
                    WHERE category = '$category'"
            );
            $qrToken = $db->single();
            $token = random_number(5);

            $lastQRToken = self::getLatest($category);
            $abspath = base64_decode($lastQRToken->full_path);

            //delete old qr
            if(file_exists($abspath)) {
                unlink($abspath);
            }
            $qrLink = self::getLink(self::LOGIN_TOKEN).'?token='.$token;
            $qrLinkEncoded = base64_encode($qrLink);

            $name = random_number(6).'.png';
            //create new path
            $abspath = PATH_UPLOAD.DS. 'images/qr/'.$name;
            $srcURL = GET_PATH_UPLOAD.'/images/qr/'.$name;

            if(!file_exists(PATH_UPLOAD.DS. 'images/qr/')) {
                mkdir(PATH_UPLOAD.DS. 'images/qr');
            }

            QRcode::png($qrLink, $abspath);
            $path = base64_encode($abspath);
            $srcURL = base64_encode($srcURL);

            $updatedAt = nowMilitary();

            if (!$qrToken) {
                //create
                $db->query(
                    "INSERT INTO qr_tokens(category,token,full_path, qr_link, src_url,updated_at)
                        VALUES('{$category}','{$token}','{$path}', '{$qrLinkEncoded}', '{$srcURL}', '{$updatedAt}')"
                );
                $db->execute();
            } else {
                //update
                $db->query(
                    "UPDATE qr_tokens
                        SET category = '{$category}', 
                            token = '{$token}', 
                            full_path = '{$path}',
                            qr_link = '{$qrLinkEncoded}',
                            src_url = '{$srcURL}',
                            updated_at = '{$updatedAt}'
                            WHERE category = '{$category}' "
                );
                $db->execute();
            }
        }
    }