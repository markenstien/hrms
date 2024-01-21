/*
change emails*/

SELECT email, SUBSTRING(email, 1, POSITION('@' in email)-1) as concat from users;
UPDATE users SET email = CONCAT(SUBSTRING(email, 1, POSITION('@' in email)-1), '@', 'korpee.app');

SELECT email, SUBSTRING(email, 1, POSITION('@' in email)-1) as concat from users;


/*
*update last name
*/

UPDATE users set lastname = lower(substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand()*36+1, RAND()*(10-5)+5));
SELECT substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', rand(@seed:=round(rand(@lid)*4294967296))*36+1, RAND()*(10-5)+5 )


DELIMITER $$
CREATE DEFINER=`root`@`%` FUNCTION `RandString`(length SMALLINT(3)) RETURNS varchar(100) CHARSET utf8
begin
    SET @returnStr = '';
    SET @allowedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    SET @i = 0;
    WHILE (@i < length) DO
        SET @returnStr = CONCAT(@returnStr, substring(@allowedChars, FLOOR(RAND() * LENGTH(@allowedChars) + 1), 1));
        SET @i = @i + 1;
    END WHILE;
    RETURN @returnStr;
END