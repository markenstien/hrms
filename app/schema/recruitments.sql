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


CREATE TABLE `recruitments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `mobile_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `position_id` int(10) DEFAULT NULL,
  `expected_salary` varchar(12) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `result` char(50) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci


alter table recruitments
    add column recruit_status enum('on-boarded','on-process', 'failed');

alter table recruitments
    add column recruit_status_by int(10);