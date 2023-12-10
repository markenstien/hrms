create table device_logins(
	id int(10) not null primary key auto_increment,
	user_id int(10) not null,
	type enum('rfid','web','biometrics' ,'qr'),
	login_key varchar(100) not null,
	created_at timestamp default now(),
	updated_at timestamp default now() ON UPDATE now()
);

create table automatic_logout_settings(
	id int(10) not null primary key auto_increment,
	user_id int(10) not null,
	max_duration smallint not null comment 'duration before logging out',
	created_at timestamp default now()
);


/*insert admin account*/

insert into users(
	id int(10) not null primary key auto_increment,
	firstname varchar(100),
	lastname varchar(100),
	mobile varchar(50),
	address text,
);


insert into users(
	firstname , lastname , mobile , type,
	username , password
)VALUES(
	'admin' , 'admin' , '11111111111' , 'admin',
	'admin' , '12345' 
);


/*add admin on staff field*/
'manager','staff','admin'

truncate automatic_logout_settings

insert into automatic_logout_settings(
	user_id , max_duration
)
(SELECT id , 30 from users)