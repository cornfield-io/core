<?php

declare(strict_types=1);

namespace Cornfield\Core;

use Cornfield\Core\Configuration\Constants;
use Cornfield\Core\Exception\ApplicationException;
use Cornfield\Core\Helper\FilesystemHelper;
use DI\ContainerBuilder;
use Exception;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Bootstrap
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var array
     */
    private $options;

    /**
     * Bootstrap constructor.
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
            $this->options = $resolver->resolve($options);

            mb_internal_encoding($this->options['charset']);
            mb_http_output($this->options['charset']);

            $this->container();
        } catch (Exception $exception) {
            throw new ApplicationException('Cannot start application', 0, $exception);
        }
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
        $files = [];

        if (null !== $this->options['path.configuration']) {
            $root = rtrim($this->options['path.configuration'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            foreach (['Configuration', 'Configuration.'.$this->options['environment']] as $file) {
                $path = $root.$file.'.php';

                if (FilesystemHelper::isFileReadable($path)) {
                    $files[] = $root;
                }
            }
        }

        $files[] = __DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Interfaces.php';
        $files[] = __DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Parameters.php';

        $builder->addDefinitions($this->options, $files);

        $container = $builder->build();

        AppFactory::setContainer($container);
        $this->app = AppFactory::create();

        $container->set(App::class, $this->app);
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
                'path.configuration' => null,
            ]
        );

        $resolver->setAllowedTypes('charset', 'string');
        $resolver->setAllowedTypes('path.configuration', ['null', 'string']);
    }
}
