<?php

namespace App\Framework\Routing;

class RouteCollection
{
    private array $routes = [];

    public function add(Route $route)
    {
        $this->routes[$route->getUrl()] = $route;
    }

    /**
     * @return array|Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRoute(string $url): ?Route
    {
        if (isset($this->routes[$url])) {
            return $this->routes[$url];
        }

        return null;
    }

}
