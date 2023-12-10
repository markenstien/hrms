create table wallets(
    id int(10) not null primary key auto_increment,
    user_id int(10) not null ,
    amount decimal(10 , 2) ,
    description varchar(100) not null,
    created_at timestamp default now()
);

-- query


DROP VIEW users_wallet;

CREATE VIEW users_wallet as  SELECT meta.user_id as user_id ,  meta.domain_user_token as user_token, 
    ifnull(sum(amount) , 0) AS wallet_total , firstname , 
    lastname , ifnull(bk_username , 'N/A') as username
    FROM users AS user

    LEFT JOIN user_meta as meta 
    ON meta.user_id = user.id 

    LEFT JOIN wallets AS wallet 
    ON wallet.user_id = user.id 

    GROUP BY user.id