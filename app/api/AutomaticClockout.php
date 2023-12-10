<?php 	



	class AutomaticClockout extends Controller

	{

		public function __construct()
		{
			$this->timeLog = model('TimelogModel');
		}

		
		public function index()
		{

			// $this->indexTwo();
			// $results = $this->timeLog->getActive();

			// $now = todayMilitary();

			// foreach($results as $key => $row)
			// {
			// 	$differenceInMinutes = timeDifferenceInMinutes($row->punch_time , $now);
				
			// 	//check if user max_duration is greater than difference then logout

			// 	$maxDuration = intval($row->automatic_logout->max_duration); 

			// 	if($differenceInMinutes >= $maxDuration)
			// 	{
			// 		$this->auto_clock_out($row->user_id);
			// 	}

			// }
			
			// dump($results);

		}

		public function indexTwo()
		{

			$results = $this->timeLog->getActive();

			$now = todayMilitary();

			foreach($results as $key => $row)
			{
				if( $row->scheduleToday ) 
				{	
					$shiftOverRemaning = timeDifference( $now , $row->scheduleToday->time_out );

					$shiftOverRemaningInMinutes = hoursToMinutes($shiftOverRemaning);

					// if shit if over 
					if($shiftOverRemaningInMinutes <= 0) 
					{
						//if shit is not ot then logout
						if(!$row->is_ot) 
						{
							return $this->auto_clock_out($row->user_id , " You are logged out because your shift is over <br/>");
						}else
						{
							if($shiftOverRemaningInMinutes >= 60) {
								return $this->auto_clock_out($row->user_id , " OT timesheets will be automatically logged out within one hour <br/>");
							}
						}
					}
				}

				$differenceInMinutes = timeDifferenceInMinutes($row->punch_time , $now);
				
				//check if user max_duration is greater than difference then logout
				$maxDuration = intval($row->automatic_logout->max_duration); 

				if($differenceInMinutes >= $maxDuration)
					$this->auto_clock_out($row->user_id);
			}

			dump($results);
		}

		//automatic logout user after 30mins

		public function auto_clock_out($userId = null , $remarks = '')

		{

			$userId = $_GET['user_id'] ?? $userId;



			if(is_null($userId)) {

				ee(api_response('User ID is not set'));

			}else
			{

				$clockOut = $this->timeLog->clockOut($userId, $remarks);
				
				if(!$clockOut){
					ee(api_response('clock out failed : ' . $this->timeLog->getErrorString() , false));
				}else{
					ee(api_response('clock out successfull'));
				}

			}

		}

	}