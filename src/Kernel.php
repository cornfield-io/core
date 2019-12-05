<?php

declare(strict_types=1);

namespace Cornfield\Core;

use Cornfield\Core\Exception\CoreException;
use Cornfield\Core\Router\Route;
use Cornfield\Core\Router\RouteCollectorProxy;
use Cornfield\Core\Router\RouteCollectorProxyInterface;
use Cornfield\Core\Router\RouteGroup;
use Cornfield\Core\Router\RouteGroupInterface;
use Cornfield\Core\Router\RouteInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy as SlimRouteCollectorProxy;

final class Kernel implements RouteCollectorProxyInterface
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Kernel constructor.
     *
     * @param array $options
     *
     * @throws CoreException
     */
    public function __construct(array $options = [])
    {
        $this->container = ContainerBuilder::build($options);
        $this->app = $this->container->get(App::class);

        $this->configure();
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->get($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function post(string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->post($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function put(string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->put($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function patch(string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->patch($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->delete($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function options(string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->options($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function any(string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->any($pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function map(array $methods, string $pattern, $callable): RouteInterface
    {
        return new Route($this->app->map($methods, $pattern, $callable));
    }

    /**
     * {@inheritdoc}
     */
    public function group(string $pattern, callable $callable): RouteGroupInterface
    {
        $factory = function (SlimRouteCollectorProxy $routeCollectorProxy) use ($callable): void {
            $callable(new RouteCollectorProxy($routeCollectorProxy));
        };

        return new RouteGroup($this->app->group($pattern, $factory));
    }

    /**
     * @return void
     */
    private function configure(): void
    {
        mb_internal_encoding($this->container->get('app.charset'));
        mb_http_output($this->container->get('app.charset'));

        if ($this->container->has('app.timezone')) {
            date_default_timezone_set($this->container->get('app.timezone'));
        }

        if (true === $this->container->get('router.cache') && null !== $this->container->get('path.cache')) {
            $this->app->getRouteCollector()->setCacheFile($this->container->get('path.cache').'routes.cache');
        }

        foreach ($this->container->get('router.middlewares.default') as $middleware) {
            if ($middleware instanceof MiddlewareInterface) {
                $this->app->addMiddleware($middleware);
            }
        }
    }
}
