<?php   

    class PayoutReleasedModel extends Model
    {
        public function getByGroup($startDate , $endDate)
        {
            $results = $this->db->query(
                " SELECT * FROM wallets 
                    WHERE date(created_at)
                        BETWEEN '$startDate' and '$endDate'"
            );
            
            return $results;
        }

        public function getByGroupTotal()
        {

        }
    }