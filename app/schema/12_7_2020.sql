select * from users
    where address = 'Guiguinto, Bulacan';


SELECT id
    FROM users
    WHERE address = 'Guiguinto, Bulacan';

SELECT SUM(amount);



SELECT *
    FROM hr_time_sheets

    WHERE user_id in(
        SELECT id
        FROM users
        WHERE address = 'Guiguinto, Bulacan'
        AND id != 57
    );

SELECT SUM(amount)
    FROM hr_time_sheets

    WHERE user_id in(
        SELECT id
        FROM users
        WHERE address = 'Guiguinto, Bulacan'
        AND id != 57
    );