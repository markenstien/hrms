alter table wallets
    add column transaction_type enum('PAYOUT' , 'TIMESHEET_APPROVAL' , 'OTHERS') default 'TIMESHEET_APPROVAL';


update wallets 
    set transaction_type = 'PAYOUT'
    WHERE description like '%Payout / Allowance%'

SELECT * FROM wallets 
    WHERE description like '%Payout / Allowance%'