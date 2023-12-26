drop if table exists holidays;
create table holidays(
    id int(10) not null primary key auto_increment,
    holiday_name varchar(100),
    holiday_name_abbr varchar(50),
    holiday_date date,
    holiday_work_type char(50),
    holiday_pay_type char(50),
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now(),
    created_by int(10)
);