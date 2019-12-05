<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteInterface as SlimRouteInterface;

final class Route implements RouteInterface
{
    /**
     * @var SlimRouteInterface
     */
    private SlimRouteInterface $route;

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
    public function add($middleware): RouteInterface
    {
        $this->route->add($middleware);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMiddleware(MiddlewareInterface $middleware): RouteInterface
    {
        $this->route->addMiddleware($middleware);

        return $this;
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
