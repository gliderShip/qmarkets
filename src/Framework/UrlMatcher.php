<?php

namespace App\Framework;

use App\Controller;
use App\Framework\Routing\Route;
use App\Framework\Routing\RouteCollection;

class UrlMatcher
{
    private Router $router;
    private RouteCollection $routeCollection;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->routeCollection = $this->router->getRouteCollection();
    }

    public function match(string $path): ?Route
    {
        $route = $this->routeCollection->getRoute($path);
        return $route;
    }
}
