<?php

namespace App\Framework;

use App\Framework\Routing\Route;
use App\Framework\Routing\RouteCollection;

class Router
{
    private RouteCollection $routeCollection;

    public function __construct(array $routesArray)
    {
        $this->routeCollection = new RouteCollection();

        foreach ($routesArray as $routeName => $details) {
            $this->checkRouteDefinition($routeName, $details);

            $route = new Route($routeName, $details['url'], $details['controller'], $details['action']);
            $this->routeCollection->add($route);
        }
    }

    public function getRouteCollection(): RouteCollection
    {
        return $this->routeCollection;
    }


    private function checkRouteDefinition($routeName, $routeDefinition)
    {
        if(empty($routeName)) {
            throw new \Exception('Route name is empty');
        }
        if(!isset($routeDefinition['url'])) {
            throw new \Exception('Route url is not defined');
        }
        if (!isset($routeDefinition['controller'])) {
            throw new \Exception('Controller not set for route: ' . $routeName);
        }
        if (!isset($routeDefinition['action'])) {
            throw new \Exception('Action not set for route: ' . $routeName);
        }
    }


}
