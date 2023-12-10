insert into automatic_logout_settings(
    user_id , max_duration
)
VALUES( (SELECT id FROM users WHERE id not in (SELECT user_id from automatic_logout_settings)) )
VALUES( SELECT id , 30 
    FROM users WHERE id not in(SELECT user_id from automatic_logout_settings) );

insert into automatic_logout_settings(
   max_duration , user_id
)VALUES(30 , (SELECT id FROM users WHERE id not in (SELECT user_id from automatic_logout_settings))



SELECT id FROM users 
    where id not in (SELECT user_id from automatic_logout_settings as lg_set);


INSERT INTO automatic_logout_settings(
    user_id , max_duration
)VALUES(
    SELECT id , 30 FROM users 
    where id not in (SELECT user_id from automatic_logout_settings)
);

INSERT INTO automatic_logout_settings(
    user_id , max_duration
)VALUES(
    33 , 30
),(34 , 30) , 
(35 , 30) , (36 , 30);