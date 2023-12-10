create table payrolls(
	id int(10) not null primary key auto_increment,
	start_date date,
	end_date date,
	created_at datetime not null,

	created_by int,
	approved_by int,
	release_date datetime,
	release_by int	
);


drop table if exists payroll_items;
create table payroll_items(
	id int(10) not null primary key auto_increment,
	payroll_id int(10) not null,
	user_id int(10),
	reg_amount_total decimal(10,2),
	reg_hours_total int(10) comment 'in minutes',
	ot_hours_total int(10) comment 'in minutes',
	no_of_days int(10),

	running_amount decimal(10,2) comment 'current earning before deducting this payroll amount',

	item_release_by int(10),
	item_release_date datetime,
	is_approved boolean DEFAULT true,
	cancelled_by int(10)
);