<?php 
	namespace Services;
	class CommonService {

		public static function months() {
			$retVal = [];

			for($i = 1; $i <= 12; $i++) {
				$dateObj   = \DateTime::createFromFormat('!m', $i);
				$retVal[] = $dateObj->format('F'); // March
			}

			return $retVal;
		}


		public static function _timeSheetComputation($timesheets, &$totalWorkHours, &$daysOfWork, &$totalAmount) {
			$lastDayOfWorkDate = null;
			foreach($timesheets as $key => $row) {
				$totalWorkHours += $row->duration;
				$totalAmount += $row->amount;
				$tSheetDateOfWork = date('Y-m-d', strtotime($row->time_in));

				if(is_null($lastDayOfWorkDate)){
					$lastDayOfWorkDate = $tSheetDateOfWork;
					$daysOfWork = 1;
				} else{
					if($lastDayOfWorkDate != $tSheetDateOfWork) {
						$lastDayOfWorkDate = $tSheetDateOfWork;
						$daysOfWork++;
					}
				}
			}
		}
	}