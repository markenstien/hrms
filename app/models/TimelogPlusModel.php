<?php

    class TimelogPlusModel extends Model
    {
        public $table = 'timelogs';
        
        /**
         * userId
         * device
         */
        public function log($logData) {
            $lastLog = $this->getLastLog($logData['userId']);
            $action = $this->typeOfAction($lastLog);
            $dateTime = nowMilitary();

            $this->_addRetval('action', $action);
            if(isEqual($action, 'login')) {
                //create new log
                $this->addMessage("Logged in Successfully");
                return parent::store([
                    'user_id' => $logData['userId'],
                    'clock_in' => $dateTime,
                    'clock_in_device' => $logData['device']
                ]);
            } else {
                $timeDifferenceInMinutes = timeDifferenceInMinutes($lastLog->clock_in, $dateTime);
                //update log
                //create timesheet
                $isUpdated = parent::update([
                    'clock_out' => $dateTime,
                    'clock_out_device' => $logData['device'],
                    'duration' => $timeDifferenceInMinutes
                ], [
                    'id' => $lastLog->id
                ]);

                if($isUpdated) {
                    //create timesheet
                    if(!isset($this->attendanceModel)) {
                        $this->attendanceModel = model('AttendanceModel');
                    }

                    $startDate = date('Y-m-d', strtotime($lastLog->clock_in));
                    $timeIn = date('H:i:s', strtotime($lastLog->clock_in));

                    $endDate = date('Y-m-d', strtotime($dateTime));
                    $timeOut = date('H:i:s', strtotime($dateTime));

                    $this->addMessage("Logged out Successfully");
                    $this->attendanceModel->manualEntry([
                        'user_id' => $logData['userId'],
                        'start_date' => $startDate,
                        'time_in' => $timeIn,
                        'end_date' => $endDate,
                        'time_out' => $timeOut,
                        'duration' => $timeDifferenceInMinutes,
                        'entry_type' => 'SHIFT_REGULAR',
                        'created_by'  => $logData['userId']   
                    ], $logData['device']);
                }
            }
        }

        public function getLastLog($userId) {
            $lastLog = parent::single([
                'user_id' => $userId
            ], '*', 'id desc');

            return $lastLog;
        }

        /**
         * return what time of action 
         * will be run
         */
        public function typeOfAction($lastLog) {
            $action = 'logout';

            if(!$lastLog) {
                $action = 'login';
            } else {
                if(!is_null($lastLog->clock_out)) {
                    $action = 'login';
                }
            }

            return $action;
        }
    }