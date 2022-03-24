<?php

namespace App\Front;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Framework\ControllerResolver;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Router;
use App\Framework\UrlMatcher;
use PHPUnit\Framework\Constraint\Constraint;

$request = Request::initialize();

$routesConfig = require '../config/routes.php';
$router = new Router($routesConfig);
$urlMatcher = new UrlMatcher($router);

$route = $urlMatcher->match($request->getPath());
//$arguments = $this->argumentResolver->getArguments($request, $controller);

if ($route === null) {
    $response = new Response('Not found', 404);
    $response->send();
}

$response = ControllerResolver::execute($route, $request);


$response->send();
