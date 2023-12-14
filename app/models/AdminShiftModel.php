<?php 

    class  AdminShiftModel extends Model
    {
        public $table = 'admin_shifts';
        public $childTable = 'admin_shift_items';

        public function updateShift($data, $shiftItems) {
            $updateShiftStatus = parent::update([
                'shift_name' => $data['shift_name'],
                'shift_description' => $data['shift_description']
            ], $data['id']);

            $this->updateShiftItems($data['id'], $shiftItems);
        }

        public function getItems($shiftId) {
            $this->db->query(
                "SELECT * FROM {$this->childTable}
                    WHERE shift_id = '{$shiftId}'"
            );

            return $this->db->resultSet();
        }

        public function updateShiftItems($shiftId, $shiftItems) {
            $sql = "";

            foreach($shiftItems as $key => $row) {
                $sql .= "UPDATE {$this->childTable} SET time_in = '{$row['time_in']}',
                    time_out = '{$row['time_out']}',
                    is_off = '{$row['rd']}'

                    WHERE shift_id = '{$shiftId}'
                    AND day = '{$row['day']}';";
            }

            $this->db->query($sql);
            return $this->db->execute();
        }
        public function addNewShifts($data, $shiftItems) {

            if(parent::single([
                'shift_name' => $data['shift_name']
            ])) {
                $this->addError("Shift name '{$data['shift_name']}' Already exists.");
                return false;
            }

            $id = parent::store([
                'shift_code' => random_number(5),
                'shift_name' => $data['shift_name'],
                'shift_description' => $data['shift_description']
            ]);

            if(!$id) 
                return false;

            $schedId = $this->newSchedule($id, $shiftItems);

            if($id && $schedId) {
                $this->addMessage("Shift {$data['shift_name']} has been created.");
                return true;
            }

            return false;
        }

        /**
         * Schedule Items
         */
        public function newSchedule($shiftId, $shiftItems)
		{
			$sql = " INSERT INTO $this->childTable(shift_id, day , time_in , time_out , is_off) VALUES ";

			foreach($shiftItems as $row => $shift)
			{
				if($row > 0)
					$sql .= ',';

				$sql .= " ('{$shiftId}','{$shift['day']}' , '{$shift['time_in']}' , 
                '{$shift['time_out']}' , '{$shift['rd']}') ";
			}

			$this->db->query($sql);
			return $this->db->insert();
		}

        public function getAll($params = []) {
            $this->db->query(
                "SELECT * FROM {$this->table}"
            );

            $shifts = $this->db->resultSet();

            foreach($shifts as $key => $row) {
                $row->items = $this->dbHelper->resultSet($this->childTable, '*', parent::convertWhere([
                    'shift_id' => $row->id
                ]));
            }

            return $shifts;
        }
    }