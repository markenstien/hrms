drop table wallet_transfers;
create table wallet_transfers(
    id int(10) not null primary key auto_increment,
    user_id int(10) not null,
    control_number varchar(12) unique,
    amount decimal( 10 , 2) ,
    transfered_to varchar(50) comment ' domain name ',
    description text ,
    created_at timestamp default now()
);