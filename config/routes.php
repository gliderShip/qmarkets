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
    'customers_add' => [
        'url' => '/api/customers',
        'controller' => 'CustomerController',
        'action' => 'add',
        'methods' => ['POST'],
    ]
];
