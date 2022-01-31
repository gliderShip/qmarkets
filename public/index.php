<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Request;
use App\Response;

$request = Request::initialize();

$name = $request->attributes->get('name', 'World');

$response = new Response(sprintf('Hello %s', htmlspecialchars($name, ENT_QUOTES, 'UTF-8')));

$response->send();
