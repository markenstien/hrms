drop table user_login_token;
create table user_login_token(
    id int(10) not null primary key auto_increment,
    user_id int(10) not null,
    status enum('active' , 'used') default 'active',
    token varchar(50)
);