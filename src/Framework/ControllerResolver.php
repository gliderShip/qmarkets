<?php

namespace App\Framework;

use App\Framework\Routing\Route;

class ControllerResolver
{
    public static function execute(Route $route, Request $request): Response
    {
        $controllerName = $route->getController();
        $controllerFQCN = 'App\Controller\\' . $controllerName;

        if (!class_exists($controllerFQCN)) {
            throw new \Exception('Controller ' . $controllerFQCN . ' not found', 404);
        }

        $controller = new $controllerFQCN();
        $method = $route->getAction();
        $handler = [$controller, $method];

        if (!is_callable($handler)) {
            throw new \Exception("The controller ->:$controllerName does not have the action ->: $method", 404);
        }


        return call_user_func($handler, $request);
    }

}
