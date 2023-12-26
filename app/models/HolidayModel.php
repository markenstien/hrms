<?php 

    class HolidayModel extends Model
    {
        public $table = 'holidays';

        public $_fillables = [
            'holiday_name',
            'holiday_name_abbr',
            'holiday_work_type',
            'holiday_pay_type',
            'holiday_date'
        ];

        public function addNew($holidayData) {
            $validColumns = parent::getFillablesOnly($holidayData);
            //validations
            if(!$this->validate($validColumns)) {
                return false;
            }
            return parent::store($validColumns);
        }

        public function updateComplete($holidayData, $id) {
            $validColumns = parent::getFillablesOnly($holidayData);
            if(!$this->validate($validColumns, $id)) {
                return false;
            }

            return parent::update($validColumns, $id);
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE ".parent::convertWhere($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY {$params['order']}";
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT BY {$params['limit']}";
            }

            $this->db->query(
                "SELECT * FROM {$this->table}
                    {$where} {$order} {$limit}"
            );

            return $this->db->resultSet();
        }

        public function validate($holidayData, $id = null) {
            //duplicate entry
            if(!empty($holidayData['holiday_name']) && !empty($holidayData['holiday_date'])) {
                $holiday = parent::single([
                    'holiday_name' => $holidayData['holiday_name'],
                    'holiday_date' => $holidayData['holiday_date']
                ]);

                if(!is_null($id) && $holiday) {
                    if($holiday->id != $id) {
                        $this->addError("Holiday already exist with same date");
                        return false;
                    }
                } else {
                    if($holiday) {
                        $this->addError("Holiday already exist with same date");
                        return false;
                    }
                }
            }

            return true;
        }
    }