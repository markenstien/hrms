drop table if exists login_sessions;
create table login_sessions(
    id int(10) not null primary key auto_increment,
    session_key varchar(100),
    created_at timestamp default now()
);