<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

use Slim\Interfaces\RouteCollectorProxyInterface as SlimRouteCollectorProxyInterface;
use Slim\Routing\RouteCollectorProxy as SlimRouteCollectorProxy;

final class RouteCollectorProxy implements RouteCollectorProxyInterface, RequestMethodInterface
{
    /**
     * @var SlimRouteCollectorProxyInterface
     */
    private $routeCollectorProxy;

    /**
     * RouteCollectorProxy constructor.
     *
     * @param SlimRouteCollectorProxyInterface $routeCollectorProxy
     */
    public function __construct(SlimRouteCollectorProxyInterface $routeCollectorProxy)
    {
        $this->routeCollectorProxy = $routeCollectorProxy;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->get($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->post($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->put($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function patch(string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->patch($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->delete($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function options(string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->options($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function any(string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->any($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function map(array $methods, string $pattern, $callable): RouteInterface
    {
        return new Route($this->routeCollectorProxy->map($methods, $pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function group(string $pattern, callable $callable): RouteGroupInterface
    {
        $factory = function (SlimRouteCollectorProxy $routeCollectorProxy) use ($callable): void {
            $callable(new RouteCollectorProxy($routeCollectorProxy));
        };

        return new RouteGroup($this->routeCollectorProxy->group($pattern, $factory));
    }
}
