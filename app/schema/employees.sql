
drop table if exists employee_datas;
create table employee_datas(
    id int(10) not null primary key auto_increment,
    user_id int(10) not null,
    hire_date date,
    shift_id int(10),
    position_id int(10),
    department_id int(10),
    created_at timestamp default now(),
    updated_at timestamp DEFAULT now()
);



drop table if exists employee_gov_ids;
create table employee_gov_ids(
    id int(10) not null primary key auto_increment,
    user_id int(10) not null,
    id_type varchar(50) comment 'SSS, PAGIBI, PHILHEALTH ETC',
    id_number varchar(50),
    is_verified boolean default false,
    created_at datetime default now(),
    last_updated datetime default now() ON UPDATE now(),
    remarks text
);

drop table if exists employee_salary;
create table employee_salary(
    id int(10) not null primary key auto_increment,
    user_id int(10) not null,
    salary_per_month decimal(10,2) not null,
    salary_per_day decimal(10,2),
    salary_per_hour decimal(10,2),
    computation_type char(50) comment 'compute daily, compute per hour, compute per month',
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now() 
);