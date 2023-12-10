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
            $qrLink = URL.'/api/Authentication/QR_AUTH_LOADER?token='.$token;
            $qrLinkEncoded = base64_encode($qrLink);

            $name = random_number(6).'.png';
            //create new path
            $abspath = PATH_UPLOAD.DS.$name;
            $srcURL = GET_PATH_UPLOAD.'/'.$name;

            QRcode::png($qrLink, $abspath);
            $path = base64_encode($abspath);
            $srcURL = base64_encode($srcURL);

            if (!$qrToken) {
                //create
                $db->query(
                    "INSERT INTO qr_tokens(category,token,full_path, qr_link, src_url)
                        VALUES('{$category}','{$token}','{$path}', '{$qrLinkEncoded}', '{$srcURL}')"
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
                            src_url = '{$srcURL}'
                            WHERE category = '{$category}' "
                );
                $db->execute();
            }
        }
    }