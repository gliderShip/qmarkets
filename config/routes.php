<?php

return [
    'home' => [
        'url' => '/',
        'controller' => 'HomeController',
        'action' => 'index',
        'methods' => ['GET'],
    ],
    'customers_get' => [
        'url' => '/api/customers/{id}',
        'controller' => 'CustomerController',
        'action' => 'get',
        'methods' => ['GET'],
    ],
    'customers_delete' => [
        'url' => '/api/customers/{id}',
        'controller' => 'CustomerController',
        'action' => 'delete',
        'methods' => ['DELETE'],
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
    'accounts_get' => [
        'url' => '/api/accounts/{id}',
        'controller' => 'AccountController',
        'action' => 'get',
        'methods' => ['GET'],
    ],
    'accounts_delete' => [
        'url' => '/api/accounts/{id}',
        'controller' => 'AccountController',
        'action' => 'delete',
        'methods' => ['DELETE'],
    ],
    'accounts_update' => [
        'url' => '/api/accounts/{id}',
        'controller' => 'AccountController',
        'action' => 'update',
        'methods' => ['PATCH'],
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
    'transactions_list' => [
        'url' => '/api/transactions',
        'controller' => 'TransactionController',
        'action' => 'list',
        'methods' => ['GET'],
    ],
    'transactions_get' => [
        'url' => '/api/transactions/{id}',
        'controller' => 'TransactionController',
        'action' => 'get',
        'methods' => ['GET'],
    ],
    'transactions_create' => [
        'url' => '/api/transactions',
        'controller' => 'TransactionController',
        'action' => 'create',
        'methods' => ['POST'],
    ],
    'transactions_account' => [
        'url' => '/api/transactions/account/{id}',
        'controller' => 'TransactionController',
        'action' => 'getAccountTransactions',
        'methods' => ['GET'],
    ],
];
