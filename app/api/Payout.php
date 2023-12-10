<?php   



    class Payout extends Controller

    {

        public function __construct()

        {

            $this->payout = model('PayoutModel');

        }  




        public function releaseByUsers()
        {

            $users = request()->inputs('users');



            $payouts = $this->payout->releaseByUsers($users);



            if(!$payouts['success'])

            {

                ee(api_response($payouts) , false);

            }else{

                ee(api_response($payouts));

            }

        }



        /*

        *TEMPORARY

        */

        public function releaseByUsersTMP()
        {

            $users = request()->inputs('users');
            $userIds = $users['users'];

            $payouts = $this->payout->releaseByUsers($userIds);

            if(!$payouts['success'])
            {
                ee(api_response($payouts) , false);
            }else{

                ee(api_response($payouts));

            }  

        }



        public function releaseByUser()
        {
            $userId = request()->input('userId');
            $payouts = $this->payout->releaseWallet($userId);

            if(!$payouts['success']){
                Flash::set( $payouts['errors'] , 'danger');
            }else{
                Flash::set( $payouts['warnings'] , 'info');
            }

            return request()->return();
        }



        public function releaseByUserTMP()
        {

            $userId = request()->input('userId');

            $payouts = $this->payout->releaseWallet($userId);

            if(!$payouts['success']){
                Flash::set( $payouts['errors'] , 'danger');
            }else{
                Flash::set( $payouts['warnings'] , 'info');
            }
            return request()->return();
        }

    }