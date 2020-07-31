<?php
namespace Frame\Routing;

class Route
{
    private $pattern, $map, $name, $destination;

    public function __construct($pattern, string $destination, array $map = [], ?string $name = null) {
        $this->pattern = '|' . $pattern . '|';
        $this->destination = $destination;
        $this->map = $map;
        $this->name = $name;
    }

    public function match(string $uri) {
        if (preg_match($this->pattern, $uri, $matches)) {

            $params = [];
            array_shift($matches);

            foreach ($matches as $m) {
                $mapEntry = array_shift($this->map);

                if (is_callable($mapEntry)) {
                    $params = array_merge($params, $mapEntry($m));
                } elseif (is_string($mapEntry)) {
                    $params[$mapEntry] = $m;
                }
            }

            return [$this->destination, $params];
        }

        return false;
    }
}
