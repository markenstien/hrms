<?php   
    class UserModel extends Model
    {
        public $table = 'users';
        public $_fillables = [
            'firstname',
            'lastname',
            'mobile_number',
            'email',
            'address',
            'profile_pic',
            'type',
            'username',
            'password',
            'is_deleted',
            'birthdate',
            'gender',
            'id',
            'external_attendance_id'
        ];

        public function addNew($userData) {
            $userFillableColumns = parent::getFillablesOnly($userData);
            $uid = $this->createUID();
            $userFillableColumns['uid'] = $uid;

            $uPrefix = strtoupper(substr($userData['firstname'],0,1). '' .substr($userData['lastname'],0,1));
            $username = $uPrefix. '' .$uid;
            $defaultPassword = '12345';

            if($duplicateUser = parent::single([
                'firstname' => $userData['firstname'],
                'lastname'  => $userData['lastname'],
                'birthdate' => $userData['birthdate'],
                'gender'    => $userData['gender']
            ])) {
                $link = wLinkDefault(_route('user:show', $duplicateUser->id), 'Show User');

                $this->addError("User Already exists in the system. {$link}");
                return false;
            }

            $userFillableColumns['username'] = $username;
            $userFillableColumns['password'] = $defaultPassword;

            if(!$this->validate($userFillableColumns)) {
                return false;
            }
            $userId = parent::store($userFillableColumns);

            if(!$userId) {
                $this->addError("Unable to save user to the database");
                return false;
            }

            $employeeModel = model('EmployeeModel');
            $employeeGovIDModel = model('EmployeeGovIDModel');
            $employeeSalaryModel = model('EmployeeSalaryModel');
            //save employee data
            $employeeData = $employeeModel->getFillablesOnly($userData);
            $employeeData['user_id'] = $userId;

            $isEmployeeActionOk = $employeeModel->addOrUpdate($employeeData);

            if(!$isEmployeeActionOk) {
                $this->addError("Employee action failed");
            }
            /**
             * list of array
             * [type,id_number]
             */
            $govermentIds = $this->convertGoviIds($userData);
            $isGovActionOk = true;
            if(!empty($govermentIds)) {
                foreach($govermentIds as $key => $row) {
                    $govIdInsert = $employeeGovIDModel->addOrUpdate([
                        'user_id' => $userId,
                        'id_type' => $row['id_type'],
                        'id_number' => $row['id_number']
                    ]);

                    if(!$govIdInsert) {
                        $isGovActionOk = false;
                    }
                }
            }

            if(!$isGovActionOk) {
                $this->addError("Gov action failed");
            }

            $employeSalaryData = $employeeSalaryModel->getFillablesOnly($userData);
            $employeSalaryData['user_id'] = $userId;

            $isEmployeeActionOk = $employeeSalaryModel->addOrUpdate($employeSalaryData);

            if(!$isEmployeeActionOk) {
                $this->addError("Employee Action failed");
            }

            if(!empty($this->getErrors())) {
                return false;
            }

            $this->_addRetval('userId', $userId);
            $this->_addRetval('usercode', $uid);
            $this->addMessage("User {$userData['firstname']} has been created successfully");

            return true;
        }

        public function updateComplete($userData, $userId) {
            $userValidColumns = parent::getFillablesOnly($userData);
            $isValidated = $this->validate($userValidColumns, $userId);

            if(!$isValidated) {
                return false;
            }            
            $isUpdateOkay = parent::update($userValidColumns, $userId);

            if(!$isUpdateOkay) {
                $this->addError("User Updated");
                return false;
            }

            $employeeModel = model('EmployeeModel');
            $employeeGovIDModel = model('EmployeeGovIDModel');
            $employeeSalaryModel = model('EmployeeSalaryModel');
            //save employee data
            $employeeData = $employeeModel->getFillablesOnly($userData);
            $employeeData['user_id'] = $userId;

            $isEmployeeActionOk = $employeeModel->addOrUpdate($employeeData);

            if(!$isEmployeeActionOk) {
                $this->addError("Employee action failed");
            }
            /**
             * list of array
             * [type,id_number]
             */
            $govermentIds = $this->convertGoviIds($userData);
            $isGovActionOk = true;
            if(!empty($govermentIds)) {
                foreach($govermentIds as $key => $row) {
                    $govIdInsert = $employeeGovIDModel->addOrUpdate([
                        'user_id' => $userId,
                        'id_type' => $row['id_type'],
                        'id_number' => $row['id_number']
                    ]);

                    if(!$govIdInsert) {
                        $isGovActionOk = false;
                    }
                }
            }

            if(!$isGovActionOk) {
                $this->addError("Gov action failed");
            }

            $employeSalaryData = $employeeSalaryModel->getFillablesOnly($userData);
            $employeSalaryData['user_id'] = $userId;

            $isEmployeeActionOk = $employeeSalaryModel->addOrUpdate($employeSalaryData, [
                'user_id' => $userId
            ]);

            if(!$isEmployeeActionOk) {
                $this->addError("Employee Action failed");
            }

            if(!empty($this->getErrors())) {
                return false;
            }

            $this->_addRetval('userId', $userId);
            $this->addMessage("User {$userData['firstname']} has been created successfully");

            return true;
        }   

        public function uploadProfile($fileName, $userId, $attributes = []) {
            if(!upload_empty($fileName)) {
                //upload files
                //check first if already exists

                if(!isset($this->_attachmentModel)) {
                    $this->_attachmentModel = model('AttachmentModel');
                    $currentProfile = $this->_attachmentModel->single([
                        'global_id' => $userId,
                        'global_key' => 'user_profile_picture'
                    ]);

                    if($currentProfile) {
                        /**
                         * delete existing files to preserve storage
                         */
                        $this->_attachmentModel->deleteWithFile($currentProfile->id);
                    }
                    return $this->_attachmentModel->upload([
                        'global_id' => $userId,
                        'global_key' => 'user_profile_picture',
                        'display_name' => random_letter(12)
                    ],'profile');
                }
            }
            $this->addError("File to upload does not exists");
            return false;
            
        }

        public function changeUsername($userId, $username, $password) {
            $user = parent::get($userId);

            if($duplicateUser = parent::single(['username' => $username])) {
                $this->addError("Username already exists");
                return false;
            }

            // if(!isEqual($user->password , $password)) {
            //     $this->addError("Unable to change username, password not matched");
            //     return false;
            // }

            return parent::update([
                'username' => $username
            ], $userId);
        }

        public function changePassword($userId, $newPassword, $password ='') {
            $user = parent::get($userId);

            // if(!isEqual($user->password, $password)) {
            //     $this->addError("Unable to change password, password not matched.");
            //     return false;
            // }

            return parent::update([
                'password' => $newPassword
            ], $userId);
        }

        private function validate(&$user_data , $id = null)
		{	
			if(!empty($user_data['email']))
			{
				$is_exist = $this->getByKey('email' , $user_data['email'])[0] ?? '';

				if( $is_exist && !isEqual($is_exist->id , $id) ){
					$this->addError("Email {$user_data['email']} already used");
					return false;
				}
			}

			if(!empty($user_data['username']))
			{
				$is_exist = $this->getByKey('username' , $user_data['username'])[0] ?? '';

				if( $is_exist && !isEqual($is_exist->id , $id) ){
					$this->addError("Username {$user_data['email']} already used");
					return false;
				}
			}

			if(!empty($user_data['mobile_number']))
			{
				$user_data['mobile_number'] = str_to_mobile($user_data['mobile_number']);

				if( !is_mobile_number($user_data['mobile_number']) ){
					$this->addError("Invalid Phone Number {$user_data['mobile_number']}");
					return false;
				}

				$is_exist = $this->getByKey('mobile_number' , $user_data['mobile_number'])[0] ?? '';

				if( $is_exist && !isEqual($is_exist->id , $id) ){
					$this->addError("Mobile Number {$user_data['mobile_number']} already used");
					return false;
				}
			}

            if(!empty($user_data['external_attendance_id'])) {
                $is_exist = $this->single([
                    'external_attendance_id' => $user_data['external_attendance_id']
                ]);
                if( $is_exist && !isEqual($is_exist->id , $id) ){
					$this->addError("External Attendance Identification already exists.");
					return false;
				}
            }

			return true;
		}

        public function getByKey($column , $value , $order = null)
		{
			if( is_null($order) )
				$order = $column;

			return parent::getAssoc($column , [
				$column => "{$value}"
			]);
		}

        private function convertGoviIds($postData) {
            $govIds = [];

            if(!empty($postData['sss_number'])) {
                $govIds[] = [
                    'id_type' => 'SSS',
                    'id_number' => $postData['sss_number']
                ];
            }

            if(!empty($postData['phil_health_number'])) {
                $govIds[] = [
                    'id_type' => 'PHILHEALTH',
                    'id_number' => $postData['phil_health_number']
                ];
            }

            if(!empty($postData['pagibig_number'])) {
                $govIds[] = [
                    'id_type' => 'PAGIBIG',
                    'id_number' => $postData['pagibig_number']
                ];
            }

            return $govIds;
        }

        private function extractGovIds($govermentIds) {
            $retVal = [];
            foreach($govermentIds as $key => $row) {
                $govKey = null;
                switch($row->id_type) {
                    case 'SSS' :
                        $govKey = 'sss_number';
                    break;

                    case 'PHILHEALTH' :
                        $govKey = 'phil_health_number';
                    break;

                    case 'PAGIBIG' :
                        $govKey = 'pagibig_number';
                    break;
                }
                $retVal[$govKey] = $row->id_number;
            }

            return $retVal;
        }


        public function getWithMeta($where = null , $order_by = null , $limit = null)
        {

            $this->meta = model('UserMetaModel');
            $this->schedule = model('ScheduleModel');


            if( !is_null($where) )
                $where = " WHERE ".parent::convertWhere($where);
            if( !is_null($order_by) )
                $order_by = "ORDER BY {$order_by}";

            if( !is_null($limit) )
                $limit = "LIMIT {$limit}";

            $this->db->query(
                "SELECT user.* , branch.branch as branch_name 
                    FROM $this->table as user
                    LEFT JOIN branches as branch 
                    ON branch.id = user.branch_id  
                    $where $order_by $limit"
            );

            $users = $this->db->resultSet();

            foreach($users as $key => $user)
            {
                $user->meta = $this->meta->single([

                    'user_id' => $user->id

                ]);

                $user->schedule = $this->schedule->getToday($user->id);
            }



            return $users;

        }

        public function getMeta($userId)
        {
            $this->userMeta = model('UserMetaModel');
            $this->timesheet = model('TimesheetModel');
            $this->automaticLogoutSetting = model('AutomaticLogoutSettingModel');
            $this->deviceLogin = model('LoginDeviceModel');
            $this->schedule = model('ScheduleModel');
            $user = $this->getWithBranch($userId);
            $user->userMeta = $this->userMeta->getByUserid($userId);
            
            $user->workHoursToday = $this->timesheet->getTotalWorkHoursInMinutes($userId);
            $user->autoLogout = $this->automaticLogoutSetting->getByUser($userId);
            $user->deviceLogin = $this->deviceLogin->getByUserAndDevice($userId);
            $user->schedule = $this->schedule->getByUser($userId);

            return $user;
        }

        public function getByUsername($username)
        {
            return parent::single([
                'username' => $username
            ]);
        }



        public function apiRegister($userData , $userMetaData)

        {

            $this->userMeta = model('UserMetaModel');



            $userId = parent::store($userData);



            if(!$userId)

                return false;

                

            $userMetaData = array_merge($userMetaData , ['user_id' => $userId]);



            $userMeta = $this->userMeta->store($userMetaData);





            //register to automatic logout



            $this->automaticLogoutSetting->store([

                'max_duration' => 30,

                'user_id'      => $userId

            ]);



            if($userId && $userMeta)

                return true;

            return false;

        }



        public function register($user , $userMeta , $logout)
        {

            $this->userMeta = model('UserMetaModel');

            $this->automaticLogoutSetting = model('AutomaticLogoutSettingModel');

            $fnamePrefix = substr($user['firstname'],0,1);
            $lnamePrefix = substr($user['lastname'],0,1);

            $UID = referenceSeries(random_number(5), 5, strtoupper($fnamePrefix."".$lnamePrefix), date('y'));

            $userId = parent::store([

                'firstname' => $user['firstname'],

                'lastname' => $user['lastname'],

                'type' => 'staff',

                'username' => get_token_random_char(5),

                'password' => '12345',

                'branch_id' => $user['branch_id'],
                
                'department' => $user['department'],
                'uid' => $UID
            ]);



            $metaId = $this->userMeta->store([

                'user_id' => $userId,

                'rate_per_hour' => $userMeta['rate_per_hour'],

                'rate_per_day'  => $userMeta['rate_per_day'],

                'work_hours'    => $userMeta['work_hours'],

                'max_work_hours'    => $userMeta['max_work_hours'],

            ]);

            $maxDuration = $this->automaticLogoutSetting->convertMaxDurationInHoursToMinutes($logout['hours'], $logout['minutes']);



            $logoutSetting = $this->automaticLogoutSetting->store([

                'user_id' => $userId,

                'max_duration' => $maxDuration

            ]);

            

            if($userId && $metaId && $logoutSetting)

                return $userId;

            return false;

        }



        public function startSession($userId)
        {
            $user = $this->get($userId);

            Session::set('auth' , [
                'id'        => $user->id,
                'uid'       => $user->uid,
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
                'type'      => $user->type,
                'department_id' => $user->branch_id,
                'profile_pic' => $user->profile_url,
                'position_name' => $user->position_name,
                'shift_name'  => $user->shift_name
            ]);

            return Session::get('auth');
        }



        public function deleteByToken($token)

        {

            /**

             * DO NOT DELETE THE USER JUST CHANGE THE TOKEN ACCESS TO 00000

             */

            $this->userMeta = model('UserMetaModel');



            $user = $this->userMeta->getByToken($token);



            if($user) 

                return $this->userMeta->update([

                    'domain_user_token' => '00000'

                ], $user->meta_id);



            return false;

        }


        /*
        *keypaired array
        */
        public function updateAll(array $values)
        {
            $valKeys = array_values($values);

            //load models

            $this->meta = model('UserMetaModel');
            $oldMetaData = $this->meta->getByUserid($values['user_id']);
            $oldUserData = $this->get($values['user_id']);

            if(isset($values['logout'])){
                $this->logout = model('AutomaticLogoutSettingModel');
                $logoutUpdate = $this->logout->update($values['logout'] , $values['logout']['id']);

                $this->addMessage("Automatic Logout setting updated");
            }

            if(isset($values['deviceLogin'])){
                $this->device = model('LoginDeviceModel');
                $deviceUpdate = $this->device->update($values['deviceLogin'] , $values['deviceLogin']['id']);

                $this->addMessage("Device Login setting updated");
            }

            if(isset($values['main'])){
                $updateMain = [];
                foreach($values['main'] as $key => $val) {
                    if(!empty($val))
                    $updateMain[$key] = $val;
                }
                parent::update($updateMain,$values['user_id']);
            }

            if(isset($values['main']['is_branch_timekeeper'])) {
                $this->updateBranchTimekeeper($values['main']['is_branch_timekeeper'], $values['user_id']);
            }
            $metaUpdate = $this->meta->updateWithRate($values['meta']);
            $updateData = $this->meta->updateData ?? false;
            
            $this->addMessage("User Meta Data Updated");

            if(!$metaUpdate)
                return false;

            $user = $this->get($values['user_id']);
            $whoIs = whoIs();

            $tableRows = '';

            if($tableRow = $this->addChangeAsTableRow("{$user->firstname} {$user->lastname}", 
                "{$oldUserData->firstname} {$oldUserData->lastname}", 'User full name')) {
                $tableRows .= $tableRow;
            }

            if($tableRow = $this->addChangeAsTableRow($updateData['rate_per_day'], $oldMetaData->rate_per_day, 'Daily Rate')) {
                $tableRows .= $tableRow;
            }

            if($tableRow = $this->addChangeAsTableRow($updateData['rate_per_day'], $oldMetaData->rate_per_day, 'Daily Rate')) {
                $tableRows .= $tableRow;
            }

            if($tableRow = $this->addChangeAsTableRow($updateData['work_hours'], $oldMetaData->work_hours, 'Work Hours')) {
                $tableRows .= $tableRow;
            }

            if($tableRow = $this->addChangeAsTableRow($updateData['rate_per_hour'], $oldMetaData->rate_per_hour, 'Hourly Rate (*Daily Rate / *Work Hours)')) {
                $tableRows .= $tableRow;
            }

            if($tableRow = $this->addChangeAsTableRow($updateData['max_work_hours'], $oldMetaData->max_work_hours, 'Maximum Work Hours')) {
                $tableRows .= $tableRow;
            }

            $message = "User {$user->firstname} {$user->lastname} has been update by {$whoIs['firstname']} {$whoIs['lastname']},
                Information changes : 
                <table> 
                    <thead>
                        <th>Attributes</th>
                        <th>From</th>
                        <th>To</th>
                    </thead>

                    <tbody>
                        {$tableRows}
                    </tbody>
                </table>";

            logger('INFO', $message,'USER_CHANGE_INFO',$values['user_id']);
            return true;
        }

        private function addChangeAsTableRow($newData, $oldData, $label) {
            $tableRow = '';
            if(!isEqual($newData, $oldData)) {
                $tableRow = "
                    <tr>
                        <td>{$label}</td>
                        <td>{$oldData}</td>
                        <td>{$newData}</td>
                    </tr>
                ";
            }
            return $tableRow;
        }

        public function updateBranchTimekeeper($isBranchTimekeeper, $userId) {
            return parent::update([
                'is_branch_timekeeper' => $isBranchTimekeeper
            ], $userId);
        }
        public function getWithBranch($userId)
        {
            $this->db->query(
                "SELECT user.* , branch.branch as branch_name 
                    FROM $this->table as user
                    LEFT JOIN branches as branch 
                    ON branch.id = user.branch_id  
                    WHERE user.id = '$userId' "
            );

            return $this->db->single();
        }

        public function update_work_time($data)
        {   
            $branch = $data['branch_id'];
            $department = $data['department'];
            $new_workTime = $data['work_hour'];

            $this->db->query(
                "SELECT * FROM users
                 WHERE branch_id = '$branch'
                 AND department = '$department'"
            );

            $users = $this->db->resultSet();

            foreach ( $users as $key => $value) {

                $this->db->query(
                    "UPDATE `user_meta` 
                     SET `work_hours`='$new_workTime',`max_work_hours`='$new_workTime'
                     WHERE user_id='$value->id'"
                );

                $this->db->execute();
            }

            return $users;

        }

        public function rollback_work_time($data)
        {   
            $branch = $data['branch_id'];
            $department = $data['department'];

            $this->db->query(
                "SELECT * FROM users INNER JOIN user_work_meta 
                 ON users.id = user_work_meta.userid
                 WHERE branch_id = '$branch'
                 AND department = '$department'"
            );

            $users = $this->db->resultSet();

            foreach ( $users as $key => $value) {

                $this->db->query(
                    "UPDATE `user_meta` 
                     SET `work_hours`='$value->work_hours',`max_work_hours`='$value->max_work_hours'
                     WHERE user_id='$value->userid'"
                );

                $this->db->execute();
            }

            return $users;

        }

        public function rollback_all_work_time()
        {   

            $this->db->query(
                "SELECT * FROM users INNER JOIN user_work_meta 
                 ON users.id = user_work_meta.userid"
            );

            $users = $this->db->resultSet();

            foreach ( $users as $key => $value) {

                $this->db->query(
                    "UPDATE `user_meta` 
                     SET `work_hours`='$value->work_hours',`max_work_hours`='$value->max_work_hours'
                     WHERE user_id='$value->userid'"
                );

                $this->db->execute();
            }

            return $users;
        }

        public function delete($id)
        {
            $user = parent::get($id);

            if(!$user) {
                $this->addError("User does not exists");
                return false;
            }

            $isOk = parent::update([
                'is_deleted' => true
            ], $id);

            if($isOk) {
                $this->addMessage("User {$user->firstname} has been deleted.");
            }

            return $isOk;
        }


        public function destroy($id)
        {
            $user = parent::get($id);

            if(!$user->is_deleted) {
                $this->addError("Account is not destroyable.");
                return false;
            }
            
            parent::delete($id);
            $this->addMessage("User {$user->firstname} has been permanently deleted.");
            return true;
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(isset($params['where'])) {
                $where = " WHERE " . parent::convertWhere($params['where']);
            }
            if(isset($params['order'])) {
                $order = " ORDER BY {$params['order']}";
            }

            if(isset($params['limit'])) {
                $limit = " LIMIT {$params['limit']}";
            }

            $this->db->query(
                "SELECT user.*,
                    concat(firstname, ' ',lastname) as fullname,
                    shift.shift_name, shift.shift_description,
                    eed.department_id, eed.position_id, eed.shift_id,
                    eed.hire_date,
                    department.branch as department_name,
                    position.position_code, position.position_name,
                    position.min_rate as position_min_rate, position.max_rate as position_max_rate,
                    ees.salary_per_month as salary_per_month ,ees.salary_per_day as salary_per_day,
                    ees.salary_per_hour as salary_per_hour,
                    ees.computation_type as computation_type,
                    att.full_url as profile_url,
                    att.full_path as profile_path

                    FROM {$this->table} as user 
                        LEFT JOIN employee_datas as eed
                            ON eed.user_id = user.id

                        LEFT JOIN branches as department
                            ON department.id = eed.department_id
                        
                        LEFT JOIN admin_shifts as shift
                            ON shift.id = eed.shift_id

                        LEFT JOIN positions as position
                            ON position.id = eed.position_id

                        LEFT JOIN employee_salary as ees 
                            ON ees.user_id = user.id

                        LEFT JOIN attachments as att 
                            ON att.global_id = user.id
                                AND att.global_key = 'user_profile_picture'


                    {$where} {$order} {$limit}"
            );
            return $this->db->resultSet();
        }

        // public function getAll($params = []) {

        //     $where = null;
        //     $order = null;
        //     $limit = null;

        //     if(isset($params['where'])) {
        //         $where = " WHERE " . parent::convertWhere($params['where']);
        //     }
        //     if(isset($params['order'])) {
        //         $order = " ORDER BY {$params['order']}";
        //     }

        //     if(isset($params['limit'])) {
        //         $limit = " LIMIT {$params['limit']}";
        //     }

            // $this->db->query(
            //     "SELECT user.*,
            //         concat(firstname, ' ',lastname) as fullname,
            //         rate_per_hour,rate_per_day,work_hours,max_work_hours,
            //         branch.branch as branch_name
            //         FROM users as user 

            //         LEFT JOIN branches as branch
            //         on branch.id = user.branch_id

            //         LEFT JOIN user_meta 
            //         on user_meta.user_id = user.id
            //         {$where} {$order} {$limit}"
            // );
            // return $this->db->resultSet();
        // }

        public function get($id) {
            if(is_array($id)) {
                $user = $this->getAll([
                    'where' => $id
                ])[0] ?? false;
            } else {
                $user = $this->getAll([
                    'where' => ['user.id' => $id]
                ])[0] ?? false;
            }
            
            if(!$user)
                return false;

            if(!isset($this->govIDModel)) {
                $this->govIDModel = model('EmployeeGovIDModel');
            }

            if($govIds = $this->extractGovIds($this->govIDModel->all([
                'user_id' => $user->id
            ]))) {
                $user->sss_number = $govIds['sss_number'];
                $user->phil_health_number = $govIds['phil_health_number'];
                $user->pagibig_number = $govIds['pagibig_number'];
            }

            return $user;
        }

        public function createUID() {
            return referenceSeries((parent::lastId() + 1), 5, null, date('y'));
        }
    }