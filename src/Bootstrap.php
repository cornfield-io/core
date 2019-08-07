<?php

declare(strict_types=1);

namespace Cornfield\Core;

use DI\ContainerBuilder;
use Exception;
use Slim\App;
use Slim\Factory\AppFactory;

final class Bootstrap
{
    /**
     * @var App
     */
    private $app;

    /**
     * Bootstrap constructor.
     *
     * @param string $charset
     *
     * @throws Exception
     */
    public function __construct(string $charset = 'UTF-8')
    {
        mb_internal_encoding($charset);
        mb_http_output($charset);

        $this->container();
    }

    public function run(): void
    {
        $this->app->run();
    }

    /**
     * @throws Exception
     */
    private function container(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(
            __DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Interfaces.php',
            __DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Parameters.php'
        );

        $container = $builder->build();

        AppFactory::setContainer($container);
        $this->app = AppFactory::create();

        $container->set(App::class, $this->app);
    }
}
