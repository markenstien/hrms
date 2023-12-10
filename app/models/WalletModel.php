<?php   
    class WalletModel extends Model
    {
        public $table = 'wallets';

        public function getByUserTotal()
        {
            $this->db->query(
                "SELECT user.username , user.firstname , user.lastname , 
                    user.mobile , ifnull(wallet.wallet_total , 0) as wallet_total FROM users as user
                    LEFT JOIN (
                        SELECT sum(ifnull(amount , 0)) as wallet_total, user_id 
                            FROM {$this->table} 
                            GROUP bY user_id
                ) as wallet

                ON user.id = wallet.user_id
                ORDER BY wallet.wallet_total desc ,  user.firstname asc
                "
            );

            return $this->db->resultSet();
        }
        
        public function getTotal($userId)
        {

            $this->db->query(

                "SELECT SUM(amount) as total 

                    FROM $this->table 

                    WHERE user_id = '$userId'"

            );



            return $this->db->single()->total ?? 0;

        }

        public function getToday($userId) {
            $today = date('Y-m-d');
            $this->db->query(
                "SELECT SUM(amount) as total
                    FROM {$this->table}
                    WHERE user_id = '{$userId}'
                    AND date(created_at) = '{$today}' "
            );
            return $this->db->single()->total ?? 0;
        }

        public function getByDate($startDate, $endDate) {
            $this->db->query(
                "SELECT user.username , user.firstname , user.lastname, 
                    branch.branch as branch_name,
                    user.branch_id as branch_id,
                    user.mobile , ifnull(wallet.wallet_total , 0) as wallet_total FROM users as user
                    LEFT JOIN (
                        SELECT sum(ifnull(amount , 0)) as wallet_total, user_id 
                            FROM {$this->table} 
                            WHERE date(created_at) >= '{$startDate}'
                            AND date(created_at) <= '{$endDate}'
                            GROUP bY user_id
                ) as wallet
                ON user.id = wallet.user_id

                LEFT JOIN branches as branch 
                ON branch.id = user.branch_id
                WHERE user.is_deleted = false
                
                ORDER BY wallet.wallet_total desc ,  
                user.firstname asc
                "
            );
            return $this->db->resultSet();
        }




        public function getWalletByUser($userId)

        {

            return $this->dbHelper->single(...[

                'users_wallet',

                '*',

                " user_id = '{$userId}' "

            ]);

        }



        public function getWithUser()

        {

            $pera = model('BankPeraModel');



            $wallets = $this->dbHelper->resultSet(...[

                'users_wallet',

                '*',

                null,

                'wallet_total desc'

            ]);

            

            foreach($wallets as $key => $row){

                $row->pera = $pera->getByUser($row->user_id);

            }



            return $wallets;

        }



        public function getWithUserByIds($userIds)

        {

            $useridString = implode("','" , $userIds);

            

            return $this->dbHelper->resultSet(...[

                'users_wallet',

                '*',

                "user_id in ('{$useridString}')"

            ]);

        }

        public function getByUser($userId)
        {
            return parent::dbgetDesc('id' ,  "user_id = '{$userId}'" );
        }

        public function addIncome($incomeData) {
            parent::store([
                'user_id' => $incomeData['user_id'],
                'amount'  => $incomeData['amount'],
                'description' => 'Timesheet Income',
                'transaction_type' => 'TIMESHEET_APPROVAL'
            ]);
        }
    }