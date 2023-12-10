<?php   

    class Wallet extends Controller
    {

        public function __construct()
        {
            $this->wallet = model('WalletModel');
        }

        public function index()
        {
            $wallets = $this->wallet->getWithUser();

            ee(api_response($wallets));
        }


        public function getUsersWallet()
        {
            $wallets = $this->wallet->getWithUser();

            ee(api_response($wallets));
        }

        public function getWithDates()
        {
            //type today
            //custom date
        }
    }