<?php

namespace App\Framework\Routing;

class RouteCollection
{
    /** @var Route[] */
    private array $routes = [];

    public function add(Route $route)
    {
        $methods = $route->getMethods();
        foreach ($methods as $method) {
            $this->routes[$route->getPath()][$method] = $route;
        }
    }

    /**
     * @return array|Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRoute(string $url, string $method): ?Route
    {
        return $this->routes[$url][$method] ?? null;
    }

}
