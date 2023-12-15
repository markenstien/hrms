create table timelogs(
    id int(10) not null primary key auto_increment,
    user_id int(10),
    clock_in datetime,
    clock_out datetime,
    duration int,
    clock_in_device char(50),
    clock_out_device char(50)
);