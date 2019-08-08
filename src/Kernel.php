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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Kernel
{
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

            if (null !== $options['path.cache']) {
                $this->app->getRouteCollector()->setCacheFile($options['path.cache'].'routes.cache');
            }
        } catch (Exception $exception) {
            throw new ApplicationException('Cannot start application', 0, $exception);
        }
    }

    public function run(): void
    {
        $this->app->run();
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

        if (null !== $options['path.configuration']) {
            $root = FilesystemHelper::path($options['path.configuration']);

            foreach (['Configuration', 'Configuration.'.$options['environment']] as $file) {
                $path = $root.$file.'.php';

                if (FilesystemHelper::isFileReadable($path)) {
                    $builder->addDefinitions($path);
                }
            }
        }

        $builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Interfaces.php');
        $builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Parameters.php');

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
                'path.cache' => null,
                'path.configuration' => null,
            ]
        );

        $resolver->setAllowedTypes('charset', 'string');
        $resolver->setAllowedTypes('path.cache', ['null', 'string']);
        $resolver->setAllowedTypes('path.configuration', ['null', 'string']);

        $normalizePath = static function (Options $options, ?string $value): ?string {
            return null === $value ? null : FilesystemHelper::path($value);
        };

        $resolver->setNormalizer('path.cache', $normalizePath);
        $resolver->setNormalizer('path.configuration', $normalizePath);
    }
}
