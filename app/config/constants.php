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

    define('SITE_NAME' , 'korpee.app');

    define('COMPANY_NAME' , 'Chromatic Softwares');

    define('COMPANY_NAME_ABBR', 'Chromatic Software');
    define('COMPANY_EMAIL', '');
    define('COMPANY_TEL', '');
    define('COMPANY_ADDRESS', '');
    define('APP_NAME', 'KORPEE');

    define('KEY_WORDS' , '');
    define('DESCRIPTION' , '#############');
    define('AUTHOR' , 'Chromatic Softwares');
    define('APP_KEY' , '');


    const HEADING_META = [
        'keywords' => 'Chromatic,Softwares, ChromaticSoftwares, Chromatic Softwares, Management Software, 
        Korpee, Korpee Timekeeping,
        Human Capital Management Software, 
        clock in software, punch clock, Optimizing Work Productivity and Efficiency, Efficiency, Work,
        Time clock, Attendance, Cloud Attendance', 'Bundy Clock',

        'description' => 'Find .',
        'og:type' => 'web',
        'og:url' => URL,
        'og:title' => 'Payroll Management System',
        'og:description' => 'Payroll Management System Software easily accessible with one click. 
        Payroll and Payslip Processing that tailored fit to the needs of your bussiness',
        'og:image' => URL.'/public/uploads/banner.jpg',
        'favicon' => URL.'/public/uploads/favicon.jpg',
    ];
?>
