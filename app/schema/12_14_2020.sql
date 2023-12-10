create table branches(
	id int(10) not null primary key auto_increment,
	branch varchar(100) not null,
	created_at timestamp default now(),
	updated_at datetime ON UPDATE CURRENT_TIMESTAMP
);


alter table users
	add column branch_id smallint(10);



drop table schedules;

create table schedules(
	id int(10) not null primary key auto_increment,
	user_id int(10) not null,
	day enum('sunday' , 'monday' , 'tuesday' , 'wednesday' , 'thursday' , 'friday' , 'saturday'),
	time_in time,
	time_out time,
	is_off boolean default false,
	created_at timestamp,
	updated_at datetime ON UPDATE now()
);


