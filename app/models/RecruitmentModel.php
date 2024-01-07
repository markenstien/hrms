<?php 

    class RecruitmentModel extends Model
    {
        public $table = 'recruitments';
        public $_fillables = [
            'firstname',
            'lastname',
            'email',
            'mobile_number',
            'address',
            'position_id',
            'expected_salary',
            'remarks',
            'result',
            'created_by',
            'created_at',
        ];

        public function addNew($recruitmentData) {
            $validColumns = parent::getFillablesOnly($recruitmentData);
            //check for validations
            $candidateId = parent::store($validColumns);

            if(!$candidateId) {
                $this->addError("Unable to add candidate, save failed");
                return false;
            }
            //for readability
            parent::_addRetval('candidateId', $candidateId);
            
            return $candidateId;
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE " . parent::convertWhere($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY ".$params['order'];
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT ".$params['limit'];
            }

            $this->db->query(
                "SELECT recruit.*,
                    position_name
                    FROM {$this->table} as recruit
                    LEFT JOIN positions as position
                        ON position.id = recruit.position_id
                    {$where} {$order} {$limit}"
            );

            return $this->db->resultSet();
        }

        public function onboard($id, $processedBy) {
            parent::update([
                'recruit_status' => 'on-boarded',
                'recruit_status_by' => $processedBy
            ], $id);
        }
    }