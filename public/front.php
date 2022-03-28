<?php

namespace App\Front;

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Framework\ControllerResolver;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Router;
use App\Framework\UrlMatcher;

$request = Request::initialize();

$routesConfig = require 'config/routes.php';
$router = new Router($routesConfig);
$urlMatcher = new UrlMatcher($router);

$urlPath = $request->getPath();
$method = $request->getMethod();
$route = $urlMatcher->match($urlPath, $method);

//$arguments = $this->argumentResolver->getArguments($request, $controller);
if ($route === null) {
    $response = new Response("Url ->:$urlPath was not found!", 404);
} else{
    $response = ControllerResolver::execute($route, $request);
}

$response->send();
