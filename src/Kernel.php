<?php

declare(strict_types=1);

namespace Cornfield\Core;

use Cornfield\Core\Exception\CoreException;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;

final class Kernel
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
