drop table if exists system_logs;
create table system_logs(
    id int(10) not null primary key auto_increment,
    user_id int(10) not null,
    log_text text,
    log_type enum('info', 'error', 'warning'),
    log_category varchar(50),
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE now()
);


alter table system_logs
    add column updated_by int;