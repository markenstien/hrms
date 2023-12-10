	SELECT ((SUM(duration)) + (7.5 * 60) ) / 60 as total_hours ,

	(( ((SUM(duration)) + (7.5 * 60) ) / 60 ) * um.rate_per_hour) + 282.29
	+  (3 * um.rate_per_hour) as estimated_salary

	FROM `hr_time_sheets` as hts
	LEFT JOIN user_meta as um 
	ON um.user_id = hts.user_id

	where hts.user_id = 128 
	and date(hts.created_at) >= date('2022-05-23') && date(hts.created_at) <= date('2022-05-27') GROUP BY hts.user_id;





SELECT ((SUM(duration)) + (7.5 * 60) ) / 60 as total_hours ,
	
    ((SUM(duration) / 60 ) * um.rate_per_hour) + 282.29 as estimated_salary

	FROM `hr_time_sheets` as hts
	LEFT JOIN user_meta as um 
	ON um.user_id = hts.user_id

	where hts.user_id = 128 
	and date(hts.created_at) >= date('2022-05-23') GROUP BY hts.user_id;


SELECT * FROM hr_time_sheets
	WHERE user_id = 128
	ORDER BY id desc;


 3hours - may(24)
 