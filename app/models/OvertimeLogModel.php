<?php

    class OvertimeLogModel extends Model
    {
        public $table_name = 'overtime_logs';
        public $table = 'overtime_logs';
        

        public function createLog($branchId, $extraTime){
            $instance = parent::single([
                'department_id' => $branchId,
                'status' => 'active'
            ], '*' , 'id desc');

            
            
            if($instance) {
                $this->addError("There is an existing active log for this one.");
                return false;
            } else {
                $overtimeID = parent::store([
                    'department_id' => $branchId,
                    'extra_time' => $extraTime,
                    'status' => 'active',
                    'start_date_time' => todayMilitary()
                ]);
                if(!isset($this->branchModel)) {
                    $this->branchModel = model('BranchModel');
                }
                $department = $this->branchModel->single([
                    'id' => $branchId
                ]);

                $whoIs = whoIs();
                $message = "An overtime record has been created by user ". $whoIs['firstname'] . ' '.$whoIs['lastname'];
                $message .= "
                    <ul>
                        <li> Department : {$department->branch}</li>
                        <li> OT Hours : {$extraTime}</li>
                    </ul>
                ";
                logger('INFO', $message, 'OVERTIME_LOGS', $whoIs['id']);

                return $overtimeID;
            }
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
            $this->db->query(
                "SELECT overtime.*, branch.branch FROM {$this->table} as overtime 
                    LEFT JOIN branches as branch
                    ON branch.id = overtime.department_id
                    {$where} {$order}"
            );

            return $this->db->resultSet();
        }

        public function complete($id) {
            $overtime = parent::get($id);

            if(isEqual($overtime->status, 'completed')) {
                $this->addError("Overtime is already completed");
                return false;
            } else {
                if(!isset($this->branchModel)) {
                    $this->branchModel = model('BranchModel');
                }
                
                $department = $this->branchModel->single([
                    'id' => $overtime->department_id
                ]);

                $whoIs = whoIs();
                $message = "An overtime record for department {$department->branch} has been completed by user ". $whoIs['firstname'] . ' '.$whoIs['lastname'];
                $message .= "
                    <ul>
                        <li> Department : {$department->branch}</li>
                        <li> OT Hours : {$overtime->extra_time}</li>
                        <li> Completed On Date : {$overtime->created_at}</li>
                    </ul>
                ";
                logger('INFO', $message, 'OVERTIME_LOGS', $whoIs['id']);

                return parent::update([
                    'status' => 'completed',
                    'end_date_time' => todayMilitary()
                ], $id);
            }
        }

        public function delete($id) {
            $overtime = parent::get($id);

            if(!isEqual($overtime->status, 'completed')) {
                $this->addError("Complete this overtime first, then you can delete this record");
                return false;
            } else {
                return parent::delete($id);
            }
        }
    }