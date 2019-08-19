<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

use Psr\Http\Server\MiddlewareInterface;

interface RouteInterface
{
    /**
     * @param MiddlewareInterface|string|callable $middleware
     *
     * @return RouteInterface
     */
    public function add($middleware): RouteInterface;

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return RouteInterface
     */
    public function addMiddleware(MiddlewareInterface $middleware): RouteInterface;

    /**
     * @param string $name
     *
     * @return RouteInterface
     */
    public function setName(string $name): RouteInterface;
}
