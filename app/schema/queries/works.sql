SELECT (SUM(duration) / 60) WORKED_HOURS, date(time_in) as IN_DATE  FROM
	hr_time_sheets
    	WHERE date(time_in) = DATE('2023-11-23')
        AND user_id = 22
        GROUP BY date(time_in);

//75


SELECT  (SUM(duration) / 60) WORKED_HOURS, SUM(amount) as CANCELLED_AMOUNT, date(time_in) as IN_DATE  FROM
	hr_time_sheets
    	WHERE date(time_in) BETWEEN '2023-11-01' AND '2023-11-22'
        AND user_id = 361 AND status = 'CANCELLED'
        GROUP BY date(time_in);


SELECT (SUM(duration) / 60) WORKED_HOURS, date(time_in) as IN_DATE  FROM
	hr_time_sheets
    	WHERE date(time_in) = DATE('2023-11-23')
        AND user_id = 22 AND status = 'CANCELLED'
        GROUP BY date(time_in);



SELECT * FROM user_meta
    where user_id = 22