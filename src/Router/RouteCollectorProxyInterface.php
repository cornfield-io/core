<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

interface RouteCollectorProxyInterface
{
    /**
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function get(string $pattern, $callable): RouteInterface;

    /**
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function post(string $pattern, $callable): RouteInterface;

    /**
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function put(string $pattern, $callable): RouteInterface;

    /**
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function patch(string $pattern, $callable): RouteInterface;

    /**
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function delete(string $pattern, $callable): RouteInterface;

    /**
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function options(string $pattern, $callable): RouteInterface;

    /**
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function any(string $pattern, $callable): RouteInterface;

    /**
     * @param string[]        $methods
     * @param string          $pattern
     * @param callable|string $callable
     *
     * @return RouteInterface
     */
    public function map(array $methods, string $pattern, $callable): RouteInterface;

    /**
     * @param string   $pattern
     * @param callable $callable
     *
     * @return RouteGroupInterface
     */
    public function group(string $pattern, callable $callable): RouteGroupInterface;
}
