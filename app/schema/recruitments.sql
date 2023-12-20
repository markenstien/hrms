drop table if exists recruitments;
create table recruitments(
    id int(10) not null primary key auto_increment,
    firstname varchar(50),
    lastname varchar(50),
    email varchar(50),
    mobile_number varchar(50),
    address text,
    position_id int(10),
    expected_salary varchar(12),
    remarks text,
    result char(50),
    created_by int(10),
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now()
);

/*
*should be named as candidate
*/
drop table if exists recruitment_interviews;
create table recruitment_interviews(
    id int(10) not null primary key auto_increment,
    interview_title varchar(100),
    interview_number tinyint,
    interview_code varchar(50) unique,
    recruitment_id int(10) not null,
    interviewer_name varchar(100),
    remarks text,
    result char(50),
    created_by int(10),
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now()
);