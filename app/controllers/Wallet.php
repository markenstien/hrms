<?php   
    class Wallet extends Controller
    {
        public function __construct()
        {
            $this->wallet = model('WalletModel');

            $this->walletTransfer = model('WalletTransferModel');

            $this->user = model('UserModel');
            $this->payout = model('PayoutModel');
        }


        public function resend($id)
        {
            $token = request()->input('token');
            $csrf = csrfGet();
            csrfReload();

            if(isEqual($token,$csrf)) {
                $this->payout->resend($id);
                Flash::set("Money is resent, please verify on pera-e using control-number :#{$this->payout->control_number}");
                return request()->return();
            }else{
                Flash::set("Invalid token,re-submition not allowed");
                return request()->return();
            }
        }

        public function index()
        {
            
            $data = [
                'title' => 'Wallets',
                'wallets' => []
            ];

            return $this->view('wallet/index', $data);
        }


        public function transfers()
        {

            $request = request()->inputs();

            $users = $this->user->getAll([
                'where' => [
                    'is_deleted' => false
                ],
                'order' => 'firstname asc'
            ]);

            $wallets = [];

            if (isset($request['btn_filter'])) {

                $where = [
                    'twallet.created_at' => [
                        'condition' => 'between',
                        'value' => [
                            $request['start_date'],
                            $request['end_date']
                        ]
                    ]
                ];

                if (!empty($request['user_id'])) {
                    $where['twallet.user_id'] = $request['user_id'];
                }

                $wallets = $this->walletTransfer->getAll([
                    'where' => $where,
                    'order' => 'twallet.id desc'
                ]);

            }
            $data = [
                'title'  => 'Wallets',
                'walletByUsers' => $wallets,
                'users' => $users,
                'token' => csrfReload()
            ];

            return $this->view('wallet/transfers', $data);
        }

        public function startSession()
        {
            
        }

    }