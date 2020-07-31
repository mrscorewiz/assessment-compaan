<?php
namespace Frame\Routing;

class Router
{
    private $routes = [];

    public function resolve($uri) : ?array
    {
        foreach ($this->routes as $route) {
            $resolution = $route->match($uri);
            if ($resolution) {
                return $resolution;
            }
        }

        return null;
    }

    public function pushRoute($pattern, string $destination, array $map = [], ?string $name = null) : self
    {
        return $this->push(new Route($pattern, $destination, $map, $name));
    }

    private function push(Route $route) : self
    {
        $this->routes []= $route;
        return $this;
    }
}

