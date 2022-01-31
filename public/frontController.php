<?php
require_once __DIR__.'/vendor/autoload.php';

use App\Request;
use App\Response;

$request = Request::createFromGlobals();
$response = new Response();

$map = [
    '/hello' => __DIR__.'/hello.php',
    '/bye'   => __DIR__.'/bye.php',
];

$path = $request->getPathInfo();
if (isset($map[$path])) {
    require $map[$path];
} else {
    $response->setStatusCode(404);
    $response->setContent('Not Found');
}

$response->send();
