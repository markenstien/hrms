<?php   
    class Payout extends Controller
    {
        public function __construct()
        {
            $this->user = model('UserModel');
            $this->wallet = model('WalletModel');
            $this->model = model('PayoutModel');
        }



        public function index()
        {
            authRequired();
            $wallets = $this->wallet->getByUserTotal();

            $data = [
                'wallets' => $wallets
            ];

            return $this->view('payout/index' , $data);
        }

        
        public function search()
        {

            authRequired();

            $data = [

                'users' => $this->user->getWithMeta()

            ];
            return $this->view('payout/search' , $data);
        }

        public function single($userId)
        {
            $res  = $this->model->releaseByUser( $userId );
            
           if(!$res['success']){
                Flash::set( $res['errors'] , 'danger');
            }else{
                Flash::set( $res['warnings'], 'info');
            }

            return request()->return();
        }

        public function multiple()
        {
            $users = request()->inputs('users');
            //extract users
            $users = $users['users'];

            $payouts = $this->model->releaseByUsers($users);
            
            if(!$payouts['success']){
                Flash::set( implode(',' , $payouts['errors']) , 'danger');
            }else{
                Flash::set( implode(',' , $payouts['warnings']) , 'info');
            }

            return request()->return();
        }

    }