<?php 

    $route->set([
    	'/join-to-pera-e/[:name :password]' => 'Login@create',
    	'/wallet-send'    => 'Wallet@create'
    ]);


    $route->set([
        '/admin-timelog-user/[:id]' => 'TimelogMetaController@log'
    ]);

