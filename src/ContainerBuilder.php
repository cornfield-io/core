<?php

declare(strict_types=1);

namespace Cornfield\Core;

use Cornfield\Core\Exception\CoreException;
use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\Helper\FilesystemHelper;
use Cornfield\Core\Helper\StringHelper;
use Cornfield\Core\Helper\SystemHelper;
use Exception;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContainerBuilder
{
    /**
     * @param array<string, mixed> $options
     *
     * @return ContainerInterface
     *
     * @throws CoreException
     */
    public static function build(array $options): ContainerInterface
    {
        try {
            $options = self::configureOptions($options);

            $containerBuilder = new \DI\ContainerBuilder();
            $containerBuilder->useAutowiring($options['container.autowiring']);
            $containerBuilder->useAnnotations($options['container.annotations']);

            if (true === $options['container.cache'] && null !== $options['path.cache']) {
                $path = $options['path.cache'];
                $containerBuilder->enableCompilation($path);

                if (true === FilesystemHelper::mkdir($path.'proxies')) {
                    $containerBuilder->writeProxiesToFile(true, $path.'proxies');
                }
            }

            $path = FilesystemHelper::path(__DIR__.DIRECTORY_SEPARATOR.'Configuration');
            $containerBuilder->addDefinitions($path.'Parameters.php', $path.'Services.php');

            foreach ($options['container.definitions.files'] as $filename) {
                $containerBuilder->addDefinitions($filename);
            }

            $containerBuilder->addDefinitions([
                'app.charset' => $options['charset'],
                'app.env' => $options['environment'],
                'path.cache' => $options['path.cache'],
            ]);

            $container = $containerBuilder->build();

            AppFactory::setContainer($container);
            $container->set(App::class, AppFactory::create());

            return $container;
        } catch (Exception $exception) {
            throw new CoreException('Cannot start application', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     *
     * @throws InvalidParameterException
     */
    private static function configureOptions(array $options): array
    {
        $environment = SystemHelper::env('APP_ENV', 'prod');

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'charset' => 'utf-8',
            'environment' => $environment,
            'container.autowiring' => true,
            'container.annotations' => false,
            'container.cache' => 'prod' === $environment,
            'container.definitions.files' => [],
            'path.cache' => null,
        ]);

        $resolver->setAllowedTypes('charset', 'string');
        $resolver->setAllowedTypes('environment', 'string');
        $resolver->setAllowedTypes('container.autowiring', 'bool');
        $resolver->setAllowedTypes('container.annotations', 'bool');
        $resolver->setAllowedTypes('container.cache', 'bool');
        $resolver->setAllowedTypes('container.definitions.files', 'string[]');
        $resolver->setAllowedTypes('path.cache', ['null', 'string']);

        $resolver->setNormalizer('path.cache', static function (Options $options, ?string $pathname): ?string {
            if (null === $pathname || StringHelper::isEmpty($pathname)) {
                return null;
            }

            $pathname = FilesystemHelper::path($pathname);

            return false === FilesystemHelper::isDirWritable($pathname) ? null : $pathname;
        });

        return $resolver->resolve($options);
    }
}
