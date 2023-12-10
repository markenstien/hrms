<?php   

    class LoginTokenModel extends Model
    {
        public $table = 'user_login_token';


        public function save($userId)
        {
            $token = get_token_random_char(50);

            $tokenid = parent::store([
                'user_id' => $userId,
                'token'   => $token
            ]);

            return $token;
        }


        public function getByToken($token)
        {
            $tokenResult = parent::single(['token' => $token]);
            
            if(isEqual($tokenResult->status ?? '', 'used'))
                return false;
            return $tokenResult;
        }

        public function getAndUpate($token)
        {
            $tokenResult = $this->getByToken($token);

            //if has token then update
            if($tokenResult)
                parent::update([
                    'status' => 'used'
                ] , $tokenResult->id);

            return $tokenResult;
        }
    }