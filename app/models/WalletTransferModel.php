<?php   



    class WalletTransferModel extends Model 

    {



        public $table = 'wallet_transfers';


        public function getAll($params = [])
        {
            $where = null;
            $order = null;


            if(isset($params['where'])) {
                $where = " WHERE " . parent::convertWhere($params['where']);
            }

            if(isset($params['order'])) {
                $order = " ORDER BY {$params['order']}";
            }

            $this->db->query(
                "SELECT twallet.* , u.firstname,u.lastname,
                concat(u.firstname, ' ',u.lastname) as fullname,account_number,
                is_resent
                
                FROM {$this->table} as twallet 

                LEFT JOIN users as u ON
                twallet.user_id = u.id 

                LEFT JOIN bank_pera_accounts as bpa
                ON bpa.user_id = u.id

                {$where} {$order}"
            );

            return $this->db->resultSet();
        }

        public function generateControlNumber()

        {   

            //initiate control number to false

            $controlNumber = false;



            while(!$controlNumber) 

            {

                $controlNumber = random_number(12);



                //check if control number exists in db

                $isExists = parent::single([

                    'control_number' => $controlNumber

                ]);



                //if exists create another control number

                if($isExists)

                    $controlNumber = false;

            }



            return $controlNumber;

        }

    }