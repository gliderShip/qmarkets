<?php

namespace App\Framework;

use App\Framework\Routing\Route;
use App\Controller;

class ControllerResolver
{
    public static function execute(Route $route, Request $request): Response
    {
        $controllerName = $route->getController();
        $controllerFQCN = 'Controller\\' . $controllerName;
        $controller = new $controllerFQCN();

        $method = $route->getAction();

        $handler = [$controller, $method];
        if (!is_callable($handler)) {
            throw new \Exception("The controller ->:$controller does not have the action ->: $method");
        }

        $args = $request->getQueryParams() ?? [];
        return call_user_func_array($handler, $args);

    }

}
