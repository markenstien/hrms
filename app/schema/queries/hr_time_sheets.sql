alter table hr_time_sheets add column
    entry_type char(50);


alter table hr_time_sheets add column
    approval_date datetime;

alter table hr_time_sheets  add column
    created_by int(10);