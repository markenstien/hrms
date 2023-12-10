drop table if exists time_logs;
create table time_logs(
	id int(10) not null primary key auto_increment,
	user_id int(10) not null,
	clock_in_time datetime not null,
	clock_out_time datetime default null,
	total_time_duration int comment 'total clock-in and out in minutes',
	approval_status enum('pending' , 'approved' , 'cancelled') default 'pending',
	is_ot boolean default false,
	created_at timestamp default now()
);

---table alteration


alter table hr_time_sheets
	add column flushed_hours int comment 'in minutes';

SELECT  FROM hr_time_sheets where remarks != '' order by id desc;



SELECT tklogs.id as log_id , concat(user.firstname , ' ' , user.lastname) as fullname ,
tklogs.remarks as flushed_out , tklogs.amount as amount , tklogs.status as payout_status ,
tklogs.created_at as date
	FROM hr_time_sheets as tklogs 
	LEFT JOIN users as user 
	ON tklogs.user_id = user.id

	WHERE remarks != '' 
	AND DATE(tklogs.date) >= DATE('2022-06-03')
	order by tklogs.id desc, user.firstname desc;





