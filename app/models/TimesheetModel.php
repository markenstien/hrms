<?php
    use Services\EmployeeService;
    load(['EmployeeService'], SERVICES);

    class TimesheetModel extends Model
    {

        public $table = 'hr_time_sheets';

        public static $STATUS_CANCEL = 'cancelled';

        public function __construct()
        {
            parent::__construct();

            $this->timesheetMeta = model('TimesheetMetaModel');
            $this->taskPhoto = model('TaskUploadModel');

            $this->user = model('UserModel');
        }

    
        public function save($timesheetData , $timesheetMetaData)
        {
            $timesheetId = parent::store($timesheetData);
            $timesheetMetaData = array_merge($timesheetMetaData , ['sheet_id' => $timesheetId]);
            $timesheetMetaId = $this->timesheetMeta->store($timesheetMetaData);
            
            if($timesheetId && $timesheetMetaId){
                $this->id = $timesheetId;
                $this->metaId = $timesheetMetaId;
                return true;
            }

            return false;
        }

        /**
         * DO NOT CHANGE.
         */

        public function computeSalaryWithDuration($salaryPerHour , $durationInMinutes)
        {   

            //convert hourly rate into minutes
            $salaryInMinutes = $salaryPerHour / 60;

            return $salaryInMinutes * $durationInMinutes;
        }

        public function getActive()
        {
            return $this->getAllWithMeta([
                'is_deleted' => false
            ]);

        }

        public function getLatest()
        {
            $timesheet = parent::single([
                'status' => 'pending'
            ] , '*' , 'id desc');

            $timesheet->meta = $this->timesheetMeta->single([
                'sheet_id' => $timesheet->id
            ]);

            $employee = $this->user->single([
                'id' => $timesheet->user_id
            ]);

            if($employee){
                $timesheet->employee_name = $employee->firstname . ' '.$employee->lastname;
            }else{
                $timesheet->employee_name = null;
            }

            return $timesheet;
        }

        public function getAll($condition = null, $order = null, $limit = null)
        {
            $where = null;
            

            if (!is_null($condition)) {
                $where = " WHERE ".$this->convertWhere($condition);
            }

            if (is_null($order)) {
                $order = " ORDER BY tklog.created_at desc ";
            } else {
                $order = " ORDER BY {$order} ";
            }

            if(!is_null($limit))
                $limit = " LIMIT {$limit} ";
                
            $this->db->query(
                "SELECT tklog.* , 
                    user.firstname , user.lastname,user.username,
                    user.uid as uid, 
                    concat(user.firstname , ' ' , user.lastname) as fullname,
                    shift.shift_name, shift.shift_description,
                    eed.department_id, eed.position_id, eed.shift_id,
                    eed.hire_date,
                    department.branch as department_name,
                    position.position_code, position.position_name,
                    position.min_rate as position_min_rate, position.max_rate as position_max_rate,
                    ees.salary_per_month as salary_per_month ,ees.salary_per_day as salary_per_day,
                    ees.salary_per_hour as salary_per_hour,
                    ees.computation_type as computation_type

                    FROM {$this->table} as tklog

                    LEFT JOIN users as user 
                    ON user.id = tklog.user_id

                    LEFT JOIN employee_datas as eed
                        ON eed.user_id = user.id

                    LEFT JOIN admin_shifts as shift
                    ON shift.id = eed.shift_id

                    LEFT JOIN positions as position
                        ON position.id = eed.position_id

                    LEFT JOIN branches as department
                    ON department.id = eed.department_id

                    LEFT JOIN employee_salary ees
                        ON ees.user_id = user.id

                    {$where}{$order}{$limit}"
            );

            $timesheets = $this->db->resultSet();
            return $timesheets;
        }

        public function groupResultByUser($results = [])
        {
            $payouts = [];

            foreach ($results as $key => $row) {
                if (!isset($payouts[$row->user_id])) {
                    $payouts[$row->user_id] = [
                        'name' => $row->fullname,
                        'username' => $row->username,
                        'amount' => $row->amount,
                        'totalDuration' => $row->duration,
                        'ratePerHour' => $row->rate_per_hour,
                        'departmentName' => $row->department_name,
                        'branchId'   => $row->department_id
                    ];
                } else {
                    $payouts[$row->user_id]['amount'] += $row->amount;
                    $payouts[$row->user_id]['totalDuration'] += $row->duration;
                }
            }

            return $payouts;
        }

        public function groupByBranch($results = [])
        {
            $retVal = [];

            foreach($results as $key => $row) {
                if(!isset($row['branchName'])) {
                    $retVal[$row['branchName']] = [];
                }
                $retVal[$row['branchName']][] = $row;
            }

            return $retVal;
        }

        public function getAllWithMeta($condition = null, $limit = null)
        {

            $timesheets = parent::all($condition , ' id desc ' , $limit);

            foreach($timesheets as $key => $timesheet) 
            {

                $timesheet->meta = $this->timesheetMeta->single([

                    'sheet_id' => $timesheet->id

                ]);

                $employee = $this->user->single([

                    'id' => $timesheet->user_id

                ]);

                
                if($employee){

                    $timesheet->employee_name = $employee->firstname . ' '.$employee->lastname;

                }else{

                    $timesheet->employee_name = null;

                }

            }


            return $timesheets;

        }

        public function getWithMeta($id)

        {

            $timesheet = parent::get($id);

            

            $timesheet->meta = $this->timesheetMeta->single([

                'sheet_id' => $timesheet->id

            ]);



            return $timesheet;

        }



        public function getWithMetaByUser($userId)
        {
            $timesheets = parent::all([

                'user_id' => $userId

            ],'id desc ');


            if(!$timesheets)

                return false;



            foreach($timesheets as $key => $timesheet)

            {

                $timeSheetMeta =  $this->timesheetMeta->single([

                    'sheet_id' => $timesheet->id

                ]);



                $timesheet->meta = $timeSheetMeta;



                if($timeSheetMeta) {

                    $timesheet->photos = $this->taskPhoto->all([

                        'log_id' => $timeSheetMeta->clock_in_id

                    ]);

                }else{

                    $timesheet->photos = [];

                }

            }



            return $timesheets;

        }


        // get user timesheet for 10 days record
        public function getByUserWeek($userId)
        {
            $dateNow = today();
            $this->db->query(

                "SELECT * FROM `hr_time_sheets` 
                 WHERE user_id = {$userId} 
                 AND DATEDIFF('{$dateNow}', DATE(created_at)) <= 10
                 ORDER BY time_in DESC"
            );

            $timesheets =  $this->db->resultSet();

            if(!$timesheets)

                return false;



            foreach($timesheets as $key => $timesheet)

            {

                $timeSheetMeta =  $this->timesheetMeta->single([

                    'sheet_id' => $timesheet->id

                ]);



                $timesheet->meta = $timeSheetMeta;



                if($timeSheetMeta) {

                    $timesheet->photos = $this->taskPhoto->all([

                        'log_id' => $timeSheetMeta->clock_in_id

                    ]);

                }else{

                    $timesheet->photos = [];

                }

            }



            return $timesheets;

        }




        public function approve($id)
        {   
            $timesheet = parent::get($id);
            if(isEqual($timesheet->status , 'approved'))
                return false;
            $updateTimesheet = parent::update([
                'status' => 'approved'
            ], $id);
            //wallet load
            $walletModel = model('WalletModel');
            //wallet insert
            $walletModel->store([
                'amount' => $timesheet->amount,
                'description' => ' Timesheet approved ',
                'user_id' => $timesheet->user_id
            ]);

            return $updateTimesheet;
        }


        public function approveBulk($timesheetIds) 
        {
            $failedJobs = [];
            $storedWalletId = [];
            //wallet load
            $walletModel = model('WalletModel');

            foreach($timesheetIds as $key => $timesheetId) {
                $storedWalletId[] = $this->approve($timesheetId);
            }

            if(!empty($failedJobs))
                return false;
            $this->results = $storedWalletId;
            return true;        
        }



        /**

         * DELETE BULK TIMESHEETS

         */

        public function deleteBulk($timesheetIds)

        {

            $timesheetIdString = implode("','" , $timesheetIds);



            //update only to is_deleted

            $this->db->query(

                " UPDATE $this->table 

                    SET is_deleted = TRUE

                    WHERE id in ('$timesheetIdString')"

            );



            return $this->db->execute();

        }



        /**

         * PERMANENTLY DELETE THE TIMESHEETS

         * accepts array or integer

         */

        public function moveToTrash($timesheetIds)

        {   

            if(is_array($timesheetIds))

            {

                $timesheetIdString = implode("','" , $timesheetIds);

                //update only to is_deleted

                $this->db->query(

                    " DELETE FROM $this->table 

                        WHERE id in ('$timesheetIdString')"

                );

            }else

            {

                $this->db->query(

                    " DELETE FROM $this->table 

                        WHERE id = '$timesheetIds' "

                );

            }

            



            return $this->db->execute();

        }



        /**

         * RESTORE DELETEED TIME SHEETS 

         * WHERE is deleted = true

         */

        public function restore($timesheetIds)

        {   

            if(is_array($timesheetIds))

            {

                $timesheetIdString = implode("','" , $timesheetIds);

                //update only to is_deleted

               

                $this->db->query(

                    " UPDATE $this->table 

                        SET is_deleted = FALSE

                        WHERE id in ('$timesheetIdString')"

                );



            }else

            {

                $this->db->query(

                    " UPDATE $this->table 

                        SET is_deleted = FALSE

                        WHERE id  = '$timesheetIds'"

                );

            }



            return $this->db->execute();

        }

        public function getTotalWorkHoursInMinutes($userId)
        {
            $today = today();
            $this->db->query(
                "SELECT * FROM $this->table 
                    WHERE date(time_in) = '{$today}'
                    AND user_id = '{$userId}'  "
            );

            $results = $this->db->resultSet();
            $totalWorkHoursInMinutes = 0;

            foreach($results as $key => $timesheet)
            {
                // $workHoursInMinutes = timeDifferenceInMinutes($timesheet->time_in , $timesheet->time_out);
                $totalWorkHoursInMinutes += $timesheet->duration;

            }
            return $totalWorkHoursInMinutes;
        }



        public function getTrash()

        {

            return $this->getAllWithMeta([

                'is_deleted' => true

            ]);

        }



        /**OVERRIDE */



        public function delete($id)

        {

            return parent::update([

                'is_deleted' => true

            ] , $id);

        }


        public function get_cancelled_timesheet($params = [])
        {
            $date = $params['date'];
            $branch_id = null;

            if(!empty($params['branch_id'])) {
                $branch_id = " AND user.branch_id = '{$params['branch_id']}' ";
            }

            $this->db->query(
                "SELECT hr_time_sheets.*, 
                    concat(firstname , ' ',lastname) as full_name,
                    branch as branch_name,
                    branch.id as branch_id
                    FROM hr_time_sheets 

                LEFT JOIN users as user 
                        on user.id = hr_time_sheets.user_id
                    LEFT JOIN branches as branch 
                        on branch.id = user.branch_id
                 WHERE  status = 'cancelled'
                 AND  DATEDIFF('{$date}', DATE(time_in)) <= 7
                 AND  hr_time_sheets.is_deleted = false
                 {$branch_id}
                 ORDER BY  id desc"
            );

            $timesheets = $this->db->resultSet();
            return $timesheets;
        }

        public function update_cancelled_timesheet($user_data, $data_update)
        {
            $date=date_create($data_update['time_in']);
            $time_in = date_format($date,"Y-m-d H:i:s");
            $date=date_create($data_update['time_out']);
            $time_out = date_format($date,"Y-m-d H:i:s");
            $WORK_HOURS = timeDifferenceInMinutes($time_in, $time_out);
            $amount = $this->computeSalaryWithDuration($user_data->rate_per_hour, $WORK_HOURS);
            $amount = number_format($amount, 2, '.', '');
            $timesheet_id = $data_update['id'];

            $timeSheet = parent::get($timesheet_id);
        
            $this->db->query(
                "UPDATE `hr_time_sheets` 
                 SET`time_in`='$time_in',`time_out`='$time_out',
                    `duration`='$WORK_HOURS',`amount`='$amount'
                WHERE id = {$timesheet_id}"
            );

            $isExecuted = $this->db->execute();

            if($isExecuted) {
                $whoIs = whoIs();
                $message = "({$whoIs['firstname']} {$whoIs['lastname']}) Changed Time sheet to";
                $message .= "<table>
                    <thead>
                        <th>Attribute</th>
                        <th>From</th>
                        <th>To</th>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Time In</td>
                            <td>{$timeSheet->time_in}</td>
                            <td>{$time_in}</td>
                        </tr>

                        <tr>
                            <td>Time Out</td>
                            <td>{$timeSheet->time_out}</td>
                            <td>{$time_out}</td>
                        </tr>

                        <tr>
                            <td>Duration</td>
                            <td>{$timeSheet->duration}</td>
                            <td>{$WORK_HOURS}</td>
                        </tr>

                        <tr>
                            <td>Amount</td>
                            <td>{$timeSheet->amount}</td>
                            <td>{$amount}</td>
                        </tr>
                    </tbody>
                </table>";
                
                logger("INFO", $message, 'TIMESHEET_CANCEL_FIX_LOG', whoIs()['id']);
            }
            // return $this->db->execute();
        }


        /**
         * new feature
         * newly added table column flushed
         */
        public function getWithDisputes($conditionParam = [])
        {
            $startDate = $conditionParam['startDate'];
            $endDate = $conditionParam['endDate'];
            $this->db->query(
                "SELECT tklog.user_id  , tklog.id as log_id , concat(user.firstname , ' ' , user.lastname) as fullname ,
                user.uid as uid, tklog.remarks as remarks , tklog.amount as amount , tklog.status as payout_status ,
                tklog.flushed_hours as flushed_hours,tklog.created_at as date,
                umt.rate_per_hour , (umt.rate_per_day * umt.work_hours) as rate_per_day,
                CASE 
                    WHEN tklog.flushed_hours >= 0 
                        THEN (tklog.flushed_hours) * (umt.rate_per_hour/60)
                    ELSE 0
                        end as flushed_hour_amount

                FROM hr_time_sheets as tklog 
                LEFT JOIN users as user 
                ON tklog.user_id = user.id
                LEFT JOIN user_meta as umt
                ON user.id = umt.user_id
                
                WHERE
                    (DATE(tklog.created_at)  BETWEEN '{$startDate}' AND '{$endDate}' AND 
                    tklog.flushed_hours > 0) OR
                    (tklog.status ='".self::$STATUS_CANCEL."' AND 
                    DATE(tklog.created_at)  BETWEEN '{$startDate}' AND '{$endDate}')
                    ORDER BY tklog.id desc
                "
            );
            return $this->db->resultSet();
        }


        /**
         * userId => [timesheets => [], name => '', timesheetdays => []]
         */
        public function groupSheetsByUser($results = [])
        {
            $retVal = [];
            
            foreach ($results as $key => $row) {
                if (is_null($row)) continue;

                if (!isset($retVal[$row->user_id])) {
                    $retVal[$row->user_id] = [
                        'user_id'  => $row->user_id,
                        'uid'      => $row->uid,
                        'username' => $row->username,
                        'fullname' => $row->fullname,
                        'rate_per_day'     => $row->salary_per_day,
                        'rate_per_hour'   => ($row->salary_per_day / 8),
                        'max_work_hours'   => EmployeeService::DEFAULT_WORK_HOURS,
                        'department_id'       => $row->department_id,
                        'department_name'     => $row->department_name,
                        'timesheets' => []
                    ];
                }

                array_push($retVal[$row->user_id]['timesheets'], $row);
            }

            return $retVal;
        }

        public function groupUserSheetsByDays(&$userTimesheets)
        {
            foreach ($userTimesheets as $key => $row) {
                if (!isset($row['timesheetByDays'])) {
                    $userTimesheets[$key]['timesheetByDays'] = [];
                }
                foreach ($row['timesheets'] as $timesheetKey => $timesheetRow) {
                    $day = date('D', strtotime($timesheetRow->time_in)); 
                    if (!isset($userTimesheets[$key]['timesheetByDays'][$day])) {
                        $userTimesheets[$key]['timesheetByDays'][$day] = [];
                    }
                    array_push($userTimesheets[$key]['timesheetByDays'][$day], $timesheetRow);
                }
            }
            return $userTimesheets;
        }

        public function groupUserSheetsByDate(&$userTimesheets) {
            foreach ($userTimesheets as $key => $row) {
                if (!isset($row['timesheetByDate'])) {
                    $userTimesheets[$key]['timesheetByDate'] = [];
                }
                $tmpTimesheetByDate = [];
                foreach ($row['timesheets'] as $timesheetKey => $timesheetRow) {
                    $date = date('Y-m-d', strtotime($timesheetRow->time_in)); 
                    if (!isset($tmpTimesheetByDate[$key]['timesheetByDate'][$date])) {
                        $tmpTimesheetByDate[$key]['timesheetByDate'][$date] = [];
                    }
                    array_push($tmpTimesheetByDate[$key]['timesheetByDate'][$date], $timesheetRow);
                }
                ksort($tmpTimesheetByDate[$key]['timesheetByDate']);
                $userTimesheets[$key]['timesheetByDate'] = $tmpTimesheetByDate[$key]['timesheetByDate'];
                if (isset($row['timesheets'])) {
                    unset($userTimesheets[$key]['timesheets']);
                }
            }
            return $userTimesheets;
        }

        
    }





    