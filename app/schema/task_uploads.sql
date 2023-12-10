create table task_uploads(
    id int(10) not null primary key auto_increment,
    log_id int(10) not null,
    file_path text,
    file_name text,
    created_at timestamp default now()
);