<?php 	
	
	/*
	*Modules quick functions
	*/
	function mGetSchedule($userId)
	{
		$todayDayName = date('l');

		return db_single('schedules', '*', [
			'user_id' => $userId,
			'day'     => $todayDayName
		]);
	}