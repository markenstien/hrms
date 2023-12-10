CREATE TABLE `users` (

 `id` int(11) NOT NULL AUTO_INCREMENT,

 `uid` text NOT NULL,

 `firstname` text NOT NULL,

 `lastname` text NOT NULL,

 `mobile` text NOT NULL,

 `address` text NOT NULL,

 `profile_pic` text DEFAULT NULL,

 `type` enum('manager','staff') NOT NULL DEFAULT 'staff',

 `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

 PRIMARY KEY (`id`)

) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4





CREATE TABLE user_meta(



    id int(10) not null primary key AUTO_INCREMENT,

    user_id int(10) not null,

    domain smallint,

    domain_user_token varchar(100),

    rate_per_hour decimal(10 , 2)

);


-- add user rate per day and work hours
alter table user_meta
    add column rate_per_day decimal(10 , 2),
    add column work_hours int(10);

alter table user_meta
    add column max_work_hours int(10)


alter table user_meta
    add column bk_username varchar(50);




alter table users 
    drop column mobile;

alter table users 
    add column birthdate date,
    add column gender enum('Male','Female')



alter table users 
    add column mobile_number varchar(50),
    add column email varchar(100)


alter table users drop column type;

alter table users add column
    type char(50);