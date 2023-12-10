<?php

	use Services\QRTokenService;
	load(['QRTokenService'], APPROOT.DS.'services');
	
	class Test extends Controller
	{

		public function migrateSalary() {	

			$db  = Database::getInstance();

			$db->query(
				"SELECT 
				hr_ts.id, concat(firstname, ' ', lastname) as display_name, 
					duration as in_minutes, (duration / 60) as in_hour, amount,
					rate_per_hour
				FROM hr_time_sheets as hr_ts
				LEFT JOIN users as user 
				on user.id = hr_ts.user_id
			
				LEFT JOIN user_meta
				on hr_ts.user_id = user_meta.user_id
				WHERE hr_ts.time_in > '2022-11-1'
				order by hr_ts.id asc"
			);

			$results = $db->resultSet();

			$invalidSalaries = [];
			$solvedCount = 0;

			$totalCorrectAmount = 0;
			$totalCurrentAmount = 0;
			foreach($results as $key => $row) {
				if(round($row->amount,2) < round($row->in_hour * $row->rate_per_hour, 2)) {
					$invalidSalaries[] = $row;
					$correctAmount = round($row->in_hour * $row->rate_per_hour, 2);

					$totalCorrectAmount += $correctAmount;
					$totalCurrentAmount += $row->amount;
					$db->query(
						"UPDATE hr_time_sheets set amount = '{$correctAmount}'
							WHERE id = '{$row->id}' "
					);

					$isSolved = $db->execute();
					$solvedCount++;
				}
			}
			
			dump([
				$invalidSalaries,
				$solvedCount,
				[
					'totalCurrentAmount' => $totalCurrentAmount,
					'totalCorrectAmount' => $totalCorrectAmount,
					'totalAdjustments'   => $totalCorrectAmount - $totalCurrentAmount
				]
			]);
		}
	}