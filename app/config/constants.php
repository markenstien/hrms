<?php

    #################################################
	##             THIRD-PARTY APPS                ##
    #################################################

    define('DEFAULT_REPLY_TO' , '');

    const MAILER_AUTH = [
        'username' => 'main@medicad.store',
        'password' => 'tmKcD#t3o@Y@',
        'host'     => 'medicad.store',
        'name'     => 'Medicad',
        'replyTo'  => 'main@medicad.store',
        'replyToName' => 'Medicad'
    ];



    const ITEXMO = [
        'key' => '',
        'pwd' => ''
    ];

	#################################################
	##             SYSTEM CONFIG                ##
    #################################################


    define('GLOBALS' , APPROOT.DS.'classes/globals');

    define('SITE_NAME' , '');

    define('COMPANY_NAME' , 'Bitmates');

    define('COMPANY_NAME_ABBR', 'Bitmates');
    define('COMPANY_EMAIL', '');
    define('COMPANY_TEL', '');
    define('COMPANY_ADDRESS', '');
    
    define('KEY_WORDS' , '');
    define('DESCRIPTION' , '#############');
    define('AUTHOR' , 'HRMS System');
    define('APP_KEY' , '');
?>