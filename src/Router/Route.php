<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

use Slim\Interfaces\RouteInterface as SlimRouteInterface;

final class Route implements RouteInterface
{
    /**
     * @var SlimRouteInterface
     */
    private $route;

    /**
     * Route constructor.
     *
     * @param SlimRouteInterface $route
     */
    public function __construct(SlimRouteInterface $route)
    {
        $this->route = $route;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): RouteInterface
    {
        $this->route->setName($name);

        return $this;
    }
}
