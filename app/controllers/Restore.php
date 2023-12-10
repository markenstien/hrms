<?php 	

	class Restore extends Controller
	{

		public function __construct()
		{
            $this->timesheet = model('TimesheetModel');
            $this->timesheetMeta = model('TimesheetMetaModel');
            $this->user  = model('UserModel');
            
            $this->db = Database::getInstance();
		}

		public function index()
		{
            $this->db->query("SELECT * FROM `hr_time_logs` WHERE id >= 446 AND type = 'time_in' ");

            $timelogsIn = $this->db->resultSet();

            foreach($timelogsIn as $key => $row) 
            {
				$this->db->query(
					"SELECT * FROM hr_time_logs
						where session = '$row->session'
						and type = 'time_out'"
				);

				$timeout = $this->db->single();

                if(!$timeout)
                    continue;
                    
				$durationInMinutes = timeDifferenceInMinutes( $row->punch_time, $timeout->punch_time );

	            $userMeta = $this->user->getMeta($row->user_id)->userMeta;
	          

	            $timeSheet = [
	                'user_id' =>  $row->user_id,
	                'time_in' =>  $row->punch_time,
	                'time_out' => $timeout->punch_time,
	                'duration' => $durationInMinutes,
	                'amount'   => $this->timesheet->computeSalaryWithDuration($userMeta->rate_per_hour, $durationInMinutes),
	                'status'   => 'pending'
	            ];

	            $timeSheetMeta = [
	                'rate'     => $userMeta->rate_per_day,
	                'clock_in_id' => $row->id,
	                'clock_out_id' => $timeout->id
	            ];

	            //check total hours
	            $timeSheet = $this->timesheet->save($timeSheet , $timeSheetMeta);
			}
		}
	}
