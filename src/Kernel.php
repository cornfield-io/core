<?php

declare(strict_types=1);

namespace Cornfield\Core;

use Cornfield\Core\Configuration\Constants;
use Cornfield\Core\Exception\ApplicationException;
use Cornfield\Core\Helper\FilesystemHelper;
use Cornfield\Core\Router\Route;
use Cornfield\Core\Router\RouteCollectorProxy;
use Cornfield\Core\Router\RouteCollectorProxyInterface;
use Cornfield\Core\Router\RouteGroup;
use Cornfield\Core\Router\RouteGroupInterface;
use Cornfield\Core\Router\RouteInterface;
use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy as SlimRouteCollectorProxy;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Kernel implements RouteCollectorProxyInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var App
     */
    private $app;

    /**
     * Kernel constructor.
     *
     * @param array $options
     *
     * @throws ApplicationException
     */
    public function __construct(array $options = [])
    {
        try {
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver);
            $options = $resolver->resolve($options);

            mb_internal_encoding($options['charset']);
            mb_http_output($options['charset']);

            $this->load($options);
            $this->configure();
        } catch (Exception $exception) {
            throw new ApplicationException('Cannot start application', 0, $exception);
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function run(): void
    {
        $this->app->run();
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

    private function configure(): void
    {
        $cache = $this->container->get('path.cache');
        if (null !== $cache) {
            $this->app->getRouteCollector()->setCacheFile($cache.'routes.cache');
        }

        foreach ($this->container->get('middleware.add.default') as $middleware) {
            if (false === ($middleware instanceof MiddlewareInterface)) {
                $middleware = $this->container->get($middleware);
            }

            $this->app->addMiddleware($middleware);
        }
    }

    /**
     * @param array $options
     *
     * @throws Exception
     */
    private function load(array $options): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($options);
        $files = [];

        if (null !== $options['path.configuration']) {
            $root = FilesystemHelper::path($options['path.configuration']);
            array_push($files, $root.'Configuration.php', $root.'Configuration.'.$options['environment'].'.php');
        }

        if (null !== $options['path.configuration.other']) {
            array_push($files, ...$options['path.configuration.other']);
        }

        foreach ($files as $file) {
            if (FilesystemHelper::isFileReadable($file)) {
                $builder->addDefinitions($file);
            }
        }

        $builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Interfaces.php');
        $builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Parameters.php');

        $this->container = $builder->build();

        AppFactory::setContainer($this->container);
        $this->app = AppFactory::create();

        $this->container->set(App::class, $this->app);
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'charset' => 'utf-8',
                'environment' => getenv('PHP_ENVIRONMENT') ?: Constants::ENV_PRODUCTION,
                'path.cache' => null,
                'path.configuration' => null,
                'path.configuration.other' => null,
            ]
        );

        $resolver->setAllowedTypes('charset', 'string');
        $resolver->setAllowedTypes('path.cache', ['null', 'string']);
        $resolver->setAllowedTypes('path.configuration', ['null', 'string']);
        $resolver->setAllowedTypes('path.configuration.other', ['null', 'string[]']);

        $normalizePath = static function (Options $options, ?string $value): ?string {
            return null === $value ? null : FilesystemHelper::path($value);
        };

        $resolver->setNormalizer('path.cache', $normalizePath);
        $resolver->setNormalizer('path.configuration', $normalizePath);
    }
}
