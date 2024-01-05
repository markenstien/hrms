
UPDATE hr_time_sheets 
	INNER JOIN employee_salary 
		ON employee_salary.user_id = hr_time_sheets.user_id
	SET amount = ((hr_time_sheets.duration/60) * employee_salary.salary_per_hour);

UPDATE hr_time_sheets
	SET duration = 480;

SELECT * FROM hr_time_sheets WHERE hr_time_sheets.user_id = (
	SELECT id from users 
		WHERE username = 'cbaad'
);


SELECT * FROM user_meta WHERE user_meta.user_id = (
	SELECT id from users 
		WHERE username = 'cbaad'
);

/**
*fix max work hours
*/
UPDATE hr_time_sheets
	INNER JOIN user_meta 
		ON user_meta.user_id = hr_time_sheets.user_id
	SET amount = (hr_time_sheets.max_work_hours * 60) * user_meta.rate_per_hour

	WHERE date(hr_time_sheets.time_in)
		BETWEEN '2023-11-17' AND '2023-11-17'
	AND hr_time_sheets.user_id = 75;


SELECT SUM(hr_time_sheets.amount), SUM(duration), user_meta.max_work_hours FROM hr_time_sheets
	INNER JOIN user_meta 
		ON user_meta.user_id = hr_time_sheets.user_id

	WHERE date(hr_time_sheets.time_in)
		BETWEEN '2023-11-20' AND '2023-11-29'
	AND hr_time_sheets.user_id = 75

	GROUP BY date(time_in);
    

SELECT SUM(hr_time_sheets.amount), SUM(hr_time_sheets.duration) / 60
	FROM hr_time_sheets
	WHERE user_id = 75
	AND date(time_in) = '2023-11-17'
	GROUP BY date(time_in);

SELECT * 
	FROM hr_time_sheets
	WHERE user_id = 75
	AND date(time_in) = '2023-11-17'


UPDATE hr_time_sheets
	INNER JOIN user_meta 
		ON user_meta.user_id = hr_time_sheets.user_id
	SET amount = (user_meta.max_work_hours * user_meta.rate_per_hour),
	status = 'approved',
	duration = (16 * 60)


	WHERE hr_time_sheets.id = 84330


    
UPDATE 
	hr_time_sheets	set status = 'cancelled'
    	WHERE id in (
        SELECT id
        FROM hr_time_sheets
        WHERE user_id = 75
        AND date(time_in) = '2023-11-17'
    )


SELECT * FROM hr_time_sheets
	WHERE id in (
		SELECT *
        FROM hr_time_sheets
        WHERE user_id = 75
        AND date(time_in) = '2023-11-17'
	);
	
SELECT concat(firstname, ' ',lastname) as fullName, hr_time_sheets.amount,
	(hr_time_sheets.duration / 60) * user_meta.rate_per_hour as updated_amount,
	user_meta.rate_per_hour as ratePerHour
	
	FROM hr_time_sheets
	LEFT JOIN user_meta
		ON user_meta.user_id = hr_time_sheets.user_id
	LEFT JOIN users 
		ON hr_time_sheets.user_id = users.id 
	WHERE date(hr_time_sheets.time_in)
		BETWEEN '2023-10-13' AND '2023-10-19';


SELECT concat(firstname, ' ',lastname) as fullName, sum(hr_time_sheets.amount),
	sum((hr_time_sheets.duration / 60) * user_meta.rate_per_hour) as updated_amount,
	user_meta.rate_per_hour as ratePerHour
	
	FROM hr_time_sheets
	LEFT JOIN user_meta
		ON user_meta.user_id = hr_time_sheets.user_id
	LEFT JOIN users 
		ON hr_time_sheets.user_id = users.id 
	WHERE date(hr_time_sheets.time_in)
		BETWEEN '2023-10-13' AND '2023-10-19'
		
	GROUP BY hr_time_sheets.user_id;


UPDATE payroll_items
	INNER JOIN user_meta
		ON user_meta.user_id = payroll_items.user_id
	SET reg_amount_total = ((payroll_items.reg_hours_total / 60) * user_meta.rate_per_hour)
	SET take_home_pay = ((payroll_items.reg_hours_total / 60) * user_meta.rate_per_hour);



SE


SELECT * FROM payroll_items
	WHERE payroll_items.user_id = (
		SELECT id from users 
			WHERE username = 'cbaad'
	);



75