<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

use Psr\Http\Server\MiddlewareInterface;

interface RouteGroupInterface
{
    /**
     * Add middleware to the route group.
     *
     * @param MiddlewareInterface|string|callable $middleware
     *
     * @return RouteGroupInterface
     */
    public function add($middleware): RouteGroupInterface;

    /**
     * Add middleware to the route group.
     *
     * @param MiddlewareInterface $middleware
     *
     * @return RouteGroupInterface
     */
    public function addMiddleware(MiddlewareInterface $middleware): RouteGroupInterface;
}
