<?php 
    namespace Services;
    /**
     * Return Values
     * Data Arrangement
     * By sheet - per department
     */
    class TimesheetService {

        public function __construct()
        {
            $this->userModel = model('UserModel');
        }
        public function importToDb($dataToImport = array()) {
            $headers = [];
            $datas   = [];

            foreach($dataToImport as $key => $row) {
                //first row is the header
                if($key == 1) {
                    $headers = [
                        $row['A'], //external_attendance_identification_id
                        $row['B'], // date_time
                        $row['C'], //device_id skip this
                        $row['D'], // 0 for in 1 for out
                    ];
                } else {
                    $date = date('Y-m-d', strtotime($row['B']));
                    $time = date('h:i:s A', strtotime($row['B']));
                    $dateTime = date('Y-m-d h:i:s A', strtotime($row['B']));
                    $arrayKeys = array_keys($datas);
                    if(!isEqual($date, $arrayKeys)) {
                        $datas[$date] = [];
                    }

                    $datas[$date][] = [
                        'date_time' => $dateTime,
                        'time' => $time,
                        'uid'  => $row['A'],
                        'action'  => $row['D'] == '0' ? 'IN' : 'OUT'
                    ];
                }
            }

            $cleanedTime = $this->filterTime($datas);
            $cleanedTime = $this->matchUsers($cleanedTime);
            return $cleanedTime;
        }


        private function matchUsers($datas) {
            
            foreach($datas as $date => &$timelogs) {
                foreach($timelogs as $key => &$row) {
                    $name = 'user not found';
                    $id = null;
                    //join user 
                    $user = $this->userModel->single([
                        'external_attendance_id' => $row['uid']
                    ]);

                    if($user) {
                        $name = $user->firstname . ' ' . $user->lastname;
                        $id = $user->id; //userid
                    } 
                    $timelogs[$key]['name'] = $name;
                    $timelogs[$key]['user_id'] = $id;
                }
            }
            return $datas;
        }
        private function filterTime($datas) {
            $cleaned = [];
            foreach($datas as $date => $timelogs) {
                if(!isset($cleaned[$date])) {
                    $cleaned[$date] = [];
                }
                foreach($timelogs as $key => $row) {
                    $cleaned[$date][$row['uid']]['uid'] = $row['uid'];
                    $cleaned[$date][$row['uid']]['date'] = $date;

                    if($row['action'] == 'IN' && empty($cleaned[$date][$row['uid']]['in'])) {
                        $cleaned[$date][$row['uid']]['in'] = $row['time'];
                    }

                    if($row['action'] == 'OUT' && empty($cleaned[$date][$row['uid']]['out'])) {
                        $cleaned[$date][$row['uid']]['out'] = $row['time'];
                    }

                    if(!empty($cleaned[$date][$row['uid']]['in']) && !empty($cleaned[$date][$row['uid']]['out'])) {
                        $durationInMinutes = timeDifferenceInMinutes(
                            $cleaned[$date][$row['uid']]['in'], $cleaned[$date][$row['uid']]['out']);

                        if(($durationInMinutes / 60) > 8) {
                                $durationInMinutes = 8 * 60; //convert time to minutes 8hours only
                            }
                        $cleaned[$date][$row['uid']]['duration_in_minutes'] = $durationInMinutes;
                    }
                }
            }

            return $cleaned;
        }
    }