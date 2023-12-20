<?php 

    class PositionModel extends Model
    {
        public $table = 'positions';
        public $_fillables = [
            'position_name',
            'min_rate',
            'max_rate'
        ];

        public function addNew($data) {
            $columns = $this->getFillablesOnly($data);
            $columns['position_code'] = strtoupper(substr($columns['position_name'],0,4).random_number(4));
            return parent::store($columns);
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE ".parent::convertWhere($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " order by {$params['order']}";
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT {$params['limit']} ";
            }

            $this->db->query(
                "SELECT * FROM {$this->table}
                    {$where} {$order} {$limit}"
            );

            return $this->db->resultSet();
        }
    }