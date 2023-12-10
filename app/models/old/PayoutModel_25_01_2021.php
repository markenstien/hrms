<?php   

    use Bank\Pera;



    require_once CLASSES.DS.'banks/Pera.php';

    

    class PayoutModel extends Model 
    {



        public $errors = [];

        public $failedJobs = [];

        public function __construct()
        {

            $this->wallet = model('WalletModel');



            $this->walletTransfer = model('WalletTransferModel');



            $this->pera = model('BankPeraModel');

        }


        public function releaseByUser($userId)

        {

           return $this->releaseWallet($userId); 

        }



        public function releaseWallet($userId)
        {

            $dateNow = now();

            $pera = new Pera();


            $failedJobs = '';


            $errors = '';


            $payout = $this->wallet->getWalletByUser($userId);

            if( $payout->wallet_total <= 0)

                return $this->setReturnData("Insufficient wallet");

            //if no pera-e account then don't allow payout.

            $peraAccount = $this->pera->getByUser($payout->user_id);                



            if(!$peraAccount) 

                return $this->setReturnData("{$payout->firstname} does not have pera-e account");



            $isAuthenticated = $pera->connectAuth($peraAccount->api_key , $peraAccount->api_secret);

            //if invalid credentials don't don't send

            if(!$isAuthenticated)

                return $this->setReturnData("{$payout->firstname} pera-e not authenticated");

            $walletAmount   = floatval($payout->wallet_total);

            $description = 'Payout / Allowance';

            $tkDescriptionToPera   = 'Breakthrough Timekeeping, ' .$description;

            try
            {

                $controlNumber = $this->walletTransfer->generateControlNumber();
                
                /**
                 * Returns wallet id and transfer_id
                 */
                $transferReturnData = $this->transfer([
                    'wallet' => [
                        'description' => $description
                    ],

                    'transfer' => [
                        'controlNumber' => $controlNumber,
                        'description'    => $tkDescriptionToPera,
                        'transfered_to'  => 'Pera-E.com'
                    ]
                ] , $payout->user_id,  $walletAmount);
                    

                if(!$transferReturnData['success'])
                    return $this->setReturnData( $transferReturnData['err'] );

                
                $walletId = $transferReturnData['walletId'];
               

                $walletTransferId = $transferReturnData['walletTransferId'];


                /**
                 * API CALL TO TRANSFER MONEY FROM THIS DOMAIN
                 * TO PERA-E
                 */
                $moneySent = $pera->sendMoney(...[$walletAmount, $controlNumber ,$tkDescriptionToPera]);

                if(!$moneySent)
                    return $this->setReturnData("Pera-e did not recieved the sent money to {$payout->firstname}");

                /**
                 * SENT MONEY SUCCESSFUL
                 */

                $accountNumber = $peraAccount->account_number;
                $accountNumberEnding = substr($accountNumber , (strlen($accountNumber) - 4) );
                
                $smsMessage = " We have transfered your wallet PHP {$walletAmount} on {$dateNow}";
                $smsMessage .= " to your pera account. account number ending in {$accountNumberEnding} ";
                $smsMessage .= "Breakthrough Transfer RefNo. : {$controlNumber}";
                

                $ownerMessage = "{$payout->firstname} {$payout->lastname} transfered his wallet to pera-e
                Amounting to {$walletAmount}";

                sendSMS($accountNumber , $smsMessage);

                sendSMS('09179491914' , $ownerMessage);

                return $this->setReturnData("Successfully sent amount to {$payout->firstname}");

                //try to send wallets
            }catch(Exception $e) {
               $errors = $e->getMessage(); 
            }

        }



        public function setReturnData($failedJobs , $errors = '')
        {

            if(!empty( $errors))
            {

                return [

                    'success' => false,

                    'errors' => $errors

                ];

            }



            return [

                'success' => true,

                'warnings' => $failedJobs

            ];

        }



        public function releaseByUsers($users)

        {   

           $errors = [];

           $failedJobs = [];

           $forPayouts = $this->wallet->getWithUserByIds($users);

		   

           $description = " Allowance Release on ".date_long(today() , 'M d ,Y');

		   /**

              * Release Payouts

              */

            foreach($forPayouts as $key => $payout)

            {

                $response = $this->releaseWallet($payout->user_id); 

                if(!$response['success'])  {

                    $errors[] = $response['errors'];

                }else{

                    $failedJobs[] = $response['warnings'];

                }

            }



            if(!empty( $errors))

            {

                return [

                    'success' => false,

                    'errors' => $errors

                ];

            }



            return [

                'success'  => true,

                'warnings' => $failedJobs

            ];

        }

        private function transfer($transferData = [] , $userId , $amount)
        {
            $wallet = $transferData['wallet'];
            $transfer = $transferData['transfer'];

            $errors = [];

            /**
             * UP LEVEL CHECK
             */

            if(! is_numeric($userId))
                $errors [] = "Invalid user";
            
            if(! is_numeric($amount))
                $errors [] = "Invalid Amount";

            if(!empty($errors)) 
                return [
                    'success' => false,
                    'err'     => implode(',' , $errors)
                ];

            if(!isset($wallet['description']) || empty($wallet['description']))
                $errors [] = " Wallet description must be set !";

            //double check

            if( !isset($transfer['controlNumber'] , 
                $transfer['description'] , $transfer['transfered_to']) ){
                    $errors [] = "Wallet Transfers must contain control number , description and transfered to data";
                }
            
            /**
             * FIRST  LEVEL CHECKING
             */

            if(!empty($errors)) 
                return [
                    'success' => false,
                    'err'     => implode(',' , $errors)
                ];

            
            if(empty($transfer['description']))
                $errors [] = " Transfer Description cannot be empty!";
            
            if(empty($transfer['transfered_to']))
                $errors [] = " Transfered To cannot be empty! ";
            
            if(empty($transfer['controlNumber']))
                $errors [] = " Control Number cannot be empty! ";

            /**
             * SECOND LEVEL CHECKING
             */
            if(!empty($errors)) 
                return [
                    'success' => false,
                    'err'     => implode(',' , $errors)
                ];
            
            $walletId = $this->wallet->store([
                'user_id' => $userId,
                'amount'  => floatval($amount * -1),
                'description' => $wallet['description'],
                'transaction_type' => Module::get('timesheet')['types']['PAYOUT']
            ]);

            $walletTransferId = $this->walletTransfer->store([
                'control_number' => $transfer['controlNumber'],
                'user_id' => $userId,
                'description' => $transfer['description'],
                'amount' => $amount,
                'transfered_to' => 'Pera-E.com'
            ]);
            
            if(!$walletId)
                $errors [] = "Wallet record failed!";

            if(!$walletTransferId)
                $errors [] = "Wallet transfer record Failed";

            
            if(!empty($errors)) 
            {
                foreach($errors as $err) {
                    $this->addError($err);
                }

                return [
                    'success' => false,
                    'err'    => implode(',' , $errors)
                ];
            }


            return [
                'success' => true,
                'walletId' => $walletId,
                'walletTransferId' => $walletTransferId
            ];
        }

    }