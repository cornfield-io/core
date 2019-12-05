<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

use Psr\Http\Server\MiddlewareInterface;
use Slim\Interfaces\RouteGroupInterface as SlimRouteGroupInterface;

final class RouteGroup implements RouteGroupInterface
{
    /**
     * @var SlimRouteGroupInterface
     */
    private SlimRouteGroupInterface $group;

    /**
     * RouteGroup constructor.
     *
     * @param SlimRouteGroupInterface $group
     */
    public function __construct(SlimRouteGroupInterface $group)
    {
        $this->group = $group;
    }

    /**
     * {@inheritdoc}
     */
    public function add($middleware): RouteGroupInterface
    {
        $this->group->add($middleware);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMiddleware(MiddlewareInterface $middleware): RouteGroupInterface
    {
        $this->group->addMiddleware($middleware);

        return $this;
    }
}
