alter table hr_time_sheets
	add column is_ot boolean default false;

alter table hr_time_logs
	add column is_ot boolean default false;