drop table if exists time_logs;
create table hr_time_logs(
	id int(10) not null primary key auto_increment,
	user_id int(10) not null,
	session char(30) not null,
	punch_time datetime,
	origin enum('web' , 'biometrics' , 'rfid' , 'app') default 'web',
	is_late boolean default false,
	type enum('time_in' , 'time_out'),
	created_at timestamp default now()
);



create table hr_time_sheets(
	id int(10) not null primary key auto_increment,
	user_id int(10) not null,
	time_in datetime,
	time_out datetime,
	duration smallint,
	amount decimal(10 , 2),
	remarks char(20) comment 'add here if timesheet is late has ot etc',
	status enum('pending' , 'approved' , 'cancelled') default 'approved',
	approved_by int(10),
	type enum('automatic' , 'manual') default 'automatic' comment 'manual is sent via form',
	created_at timestamp default now(),
	updated_at timestamp default now() ON UPDATE CURRENT_TIMESTAMP
);


drop table hr_time_sheet_meta;
create table hr_time_sheet_meta(
	id int(10) not null primary key auto_increment,
	sheet_id int(10) not null,
	time_in_sched time,
	time_out_sched time,
	rate decimal(10 , 2),
	clock_in_id int(10) comment 'time_logs clockin id',
	clock_out_id int(10) comment 'time_logs clockout id',
	created_at timestamp default now()
);


alter table hr_time_sheet_meta 
	add column staff_note varchar(100) comment 'notes by user who created the timesheet' after bonus_amount ;


truncate hr_time_logs;
truncate hr_time_sheets;
truncate hr_time_sheet_meta;
truncate users;
truncate user_login_token;
truncate user_meta;