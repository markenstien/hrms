<?php 	

	class ClockOutModel extends Model
	{

		public $table = 'hr_time_logs';
		public $retVal;

		public function __construct()
		{

			parent::__construct();

			$this->timelog = model('TimelogModel');

			$this->timesheetModel = model('TimesheetModel');

			$this->payoutModel = model('PayoutModel');
		}

		public function clockOut($userId)
		{	
			$today = todayMilitary('TimesheetModel');

			$clockOut = $this->timelog->clockOut($userId);

			$returnedValues = $this->timelog->retVal;

			$lastPunch = $this->timelog->lastPunch;


			if(!$clockOut){
				$this->addError( $this->timelog->getErrorString() );
				return false;
			}	

			//date diference

			$timeDifferenceInMinutes = timeDifferenceInMinutes( $today ,  $lastPunch->punch_time);

			$timeInHourFormat = minutesToHours($timeDifferenceInMinutes);

			//if true then timesheet is error
			if($timeDifferenceInMinutes > 270) 
			{

				$isOK = $this->timesheetModel->update([
					'status' => 'cancelled',
					'remarks' => " You're account must logout aleast every 4hours,
					hours flushed. {$timeInHourFormat}"
				] , $returnedValues['timeSheetId']);

				$this->addError(
					"Timesheet has been cancelled , your worked over 4hours"
				);

				return false;
			}else{

				$isOk = $this->timesheetModel->approve( $returnedValues['timeSheetId']);
				// $isPayout = $this->payoutModel->releaseByUser( $userId );

				if( $isOk && $isPayout['success'])
					return true;

				return false;
			}

			//clockout okay
			//send money to pera
		}
	}