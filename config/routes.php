<?php

return [
    'home' => [
        'url' => '/',
        'controller' => 'HomeController',
        'action' => 'index',
        'methods' => ['GET'],
    ],
    'customers_list' => [
        'url' => '/api/customers',
        'controller' => 'CustomerController',
        'action' => 'list',
        'methods' => ['GET'],
    ],
    'customers_create' => [
        'url' => '/api/customers',
        'controller' => 'CustomerController',
        'action' => 'create',
        'methods' => ['POST'],
    ],
    'accounts_list' => [
        'url' => '/api/accounts',
        'controller' => 'AccountController',
        'action' => 'list',
        'methods' => ['GET'],
    ],
    'account_create' => [
        'url' => '/api/accounts',
        'controller' => 'AccountController',
        'action' => 'create',
        'methods' => ['POST'],
    ],
];
