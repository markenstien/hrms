drop table if exists admin_shifts;
create table admin_shifts(
    id int(10) not null primary key auto_increment,
    shift_code varchar(50),
    shift_name varchar(100),
    shift_description text,
    created_at timestamp default now(),
    updated_at timestamp default now() ON UPDATE NOW()
);

drop table if exists admin_shift_items;
create table admin_shift_items(
    id int(10) not null primary key auto_increment,
    shift_id varchar(100),
    day enum('sunday','monday','tuesday','wednesday','thursday','friday','saturday'),
    time_in time,
    time_out time,
    is_off boolean default false,
    created_at timestamp default now()
);