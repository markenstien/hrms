DELETE FROM users where type != 'admin';


truncate hr_time_logs;
truncate employment_leaves;
truncate hr_time_sheets;
truncate employee_salary;
truncate employee_gov_ids;
truncate employee_datas;
truncate deduction_payments;
truncate deduction_items;

truncate payrolls;
truncate payroll_items;
truncate recruitments;
truncate recruitment_interviews;
truncate attachments;
