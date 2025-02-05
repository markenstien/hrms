<?php 
    /**
     * Upgraded version of timesheetmetamodel
     * this model can process or attendance related process
     * OT,AC,MANUAL, OR API
     */
    class AttendanceModel extends Model
    {
        /**
         * table that will hold the information
         */
        public $table = 'hr_time_sheets';
        public $_fillables = [
            'user_id',
            'time_in',
            'time_out',
            'duration',
            'amount',
            'remarks',
            'status',
            'type',
            'entry_type',
            'created_by'
        ];

        public function clockIn() {
            
        }

        public function clockOut() {

        }

        
        public function manualEntry($entryData, $type = 'manual') {
            $_fillables = parent::getFillablesOnly($entryData);

            if(!isset($this->employeeSalaryModel)) {
                $this->employeeSalaryModel = model('EmployeeSalaryModel');
            }

            $userSalary = $this->employeeSalaryModel->single([
                'user_id' => $_fillables['user_id']
            ]);
            
            if(!$userSalary) {
                $this->addError("User has no salary infor, unable to compute timesheet.");
                return false;
            }
            $convertedTimeMinutes = $this->convertTimeToMinutes(...[
                $entryData['start_date'],
                $entryData['time_in'],
                $entryData['end_date'],
                $entryData['time_out'],
            ]);

            $timeInMinutes   = $this->concatDateAndTime($entryData['start_date'], $entryData['time_in']);
            $timeOutInMinutes = $this->concatDateAndTime($entryData['end_date'], $entryData['time_out']);
            //if greater than 8hrs
            if(($convertedTimeMinutes / 60) > 8) {
                $convertedTimeMinutes = 8 * 60; //convert time to minutes 8hours only
            }


            // $amount = ($convertedTimeMinutes / 60) * $userSalary->salary_per_hour;
            /**
             * temporary fix must add if user salary will be based 
             * on time or per day or monthly.
             */
            $amount = ($convertedTimeMinutes / 60) * ($userSalary->salary_per_day / 8);
            
            $isOk = parent::store([
                'user_id' => $_fillables['user_id'],
                'time_in' => $timeInMinutes,
                'time_out' => $timeOutInMinutes,
                'duration' => $convertedTimeMinutes,
                'amount'   => $amount,
                'status'   => 'pending',
                'type'     => $type,
                'entry_type' => $_fillables['entry_type'],
                'created_by' => $_fillables['created_by'] ?? whoIs('id')
            ]);

            if(!$isOk) {
                $this->addError("Unable to save timesheet");
                return false;
            }

            parent::_addRetval('attendanceId', $isOk);
            return true;
        }

        private function convertTimeToMinutes($startDate, $startTime, $endDate, $endTime) {
            $startDateTime = $this->concatDateAndTime($startDate, $startTime);
            $endDateTime = $this->concatDateAndTime($endDate, $endTime);

            $timeInMinutes = dateDifferenceInMinutes($startDateTime, $endDateTime);
            return $timeInMinutes;
        }

        private function concatDateAndTime($date, $time) {
            return date('Y-m-d H:i:s', strtotime($date . ' '. $time));
        }

        public function newEntry($entryData) {
            if(!isset($entryData['action_type'])) {
                $this->addError("Entry type must be filled");
                return false;
            }

            $actionType = $entryData['action_type'];

            switch($actionType) {
                case 'clock_in';
                    
                break;

                case 'clock_out';

                break;
            }
        }

        public function getLastLog($userId) {
            $log = parent::single([
                'where' => [
                    'user_id' => $userId
                ]
            ], '*', 'id desc');

            return $log;
        }

        public function logType($log) {
            if(is_null($log->time_out)) {
                return 'clock_in';
            } else {
                return 'clock_out';
            }
        }

        public function approve($timesheetId, $approverId) {
            return parent::update([
                'approved_by' => $approverId,
                'approval_date' => nowMilitary(),
                'status' => 'approved'
            ], $timesheetId);
        }

        public function cancel($timesheetId, $approverId) {
            $date = nowMilitary();
            return parent::update([
                'approved_by' => $approverId,
                'approval_date' => $date,
                'status' => 'cancelled',
                'updated_at' => $date
            ], $timesheetId);
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE " . parent::convertWhere($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY {$params['order']}";
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT {$params['limit']} ";
            }

            $this->db->query(
                "SELECT timesheet.*,
                    concat(user.firstname, ' ', user.lastname) as fullname,
                    user.uid as uid,
                    concat(approver.firstname, ' ', approver.lastname) as approver_name,
                    user.firstname as lastname,user.lastname as lastname,
                    
                    ees.salary_per_month as salary_per_month, 
                    ees.salary_per_day as salary_per_day, ees.salary_per_hour as salary_per_hour,
                    ees.computation_type as computation_type

                    FROM {$this->table} as timesheet
                    LEFT JOIN users as user
                        ON user.id = timesheet.user_id

                    LEFT JOIN users as approver
                        ON approver.id = timesheet.approved_by

                    LEFT JOIN employee_salary as ees
                        ON ees.user_id = user.id
                    
                    {$where} {$order} {$limit}"
            );

            return $this->db->resultSet();
        }
    }