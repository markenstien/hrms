<?php 
    class SystemLogModel extends Model
    {
        public $table = 'system_logs';
        
        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE ". parent::convertWhere($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY {$params['order']} ";
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT {$params['limit']} ";
            }

            $this->db->query(
                "SELECT sl.*, concat(up_user.firstname , ' ', up_user.lastname) as updated_by_name,
                    concat(user.firstname , ' ', user.lastname)
                    FROM {$this->table} as sl
                        LEFT JOIN users as up_user
                        ON up_user.id = sl.updated_by

                        LEFT JOIN users as user
                        ON  user.id = sl.user_id
                {$where} {$order} {$limit}"
            );

            return $this->db->resultSet();
        }
    }