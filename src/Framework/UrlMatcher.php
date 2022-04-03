<?php

namespace App\Framework;

use App\Framework\Routing\Route;
use App\Framework\Routing\RouteCollection;

class UrlMatcher
{
    private Router $router;
    private RouteCollection $routeCollection;

    private array $words = [
        'hello',
        'world',
        'foo',
        'bar',
    ];

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->routeCollection = $this->router->getRouteCollection();
    }

    public function match(Request $request, string $method): ?Route
    {
        $relativeUrl = $request->getPath();

        $route = $this->routeCollection->getRoute($relativeUrl, $method);
        if (!$route) {
            $route = $this->findDynamicRoute($request, $method);
        }

        return $route;
    }

    private function findDynamicRoute(Request $request, string $method): ?Route
    {
        $relativeUrl = $request->getPath();
        $routes = $this->routeCollection->getRoutes();

        $bestMatch = null;
        $bestMatchLength = 0;

        foreach ($routes as $path => $value) {
            /** @var ?Route $route */
            $route = $value[$method] ?? null;
            if ($route) {
                if (preg_match_all('/\/({[a-zA-Z0-9]+})/', $path, $matches)) {
                    $var = $matches[1][0] ?? null;
                    if ($var) {
                        $prefix = str_replace($var, '', $path);
                        if (strpos($relativeUrl, $prefix) === 0 && strlen($prefix) > $bestMatchLength) {
                            $routeParam = str_replace($prefix, '', $relativeUrl);
                            $route->setParameter($routeParam);
                            $request->setDynamicParameter($routeParam);
                            $bestMatch = $route;
                            $bestMatchLength = strlen($prefix);
                        }
                    }
                }
            }
        }

        return $bestMatch;
    }

}
