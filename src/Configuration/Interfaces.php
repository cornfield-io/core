<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use Buzz\Browser;
use Buzz\Client\Curl;
use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\Http\Factory\RequestFactory;
use Cornfield\Core\Http\Factory\ResponseFactory;
use Cornfield\Core\I18n\I18nInterface;
use Cornfield\Core\I18n\JsonI18n;
use Cornfield\Core\Session\NativeSession;
use Cornfield\Core\Session\SessionInterface;
use Cornfield\Core\Template\TemplateInterface;
use Cornfield\Core\Template\TwigExtension\UniversalExtension;
use Cornfield\Core\Template\TwigTemplate;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Psr16Cache;

return [
    CacheInterface::class => static function (ContainerInterface $container): CacheInterface {
        $path = $container->get('path.cache');

        if (null !== $path) {
            return new Psr16Cache(new PhpFilesAdapter('Cornfield', 0, (string) $path));
        }

        return new Psr16Cache(new NullAdapter());
    },
    ClientInterface::class => static function (): ClientInterface {
        $client = new Curl(new ResponseFactory());

        return new Browser($client, new RequestFactory());
    },
    I18nInterface::class => static function (ContainerInterface $container): I18nInterface {
        if (false === $container->has('i18n.path.language.default')) {
            throw new InvalidParameterException('The key "i18n.path.language.default" is undefined');
        }

        return new JsonI18n(
            $container->get(CacheInterface::class),
            (string) $container->get('i18n.path.language.default'),
            $container->has('i18n.path.language.callback') ? (string) $container->has('i18n.path.language.callback') : ''
        );
    },
    SessionInterface::class => static function (ContainerInterface $container): SessionInterface {
        $options = [];

        if ($container->has('session.options')) {
            $options = $container->get('session.options');
        }

        $options += ['name' => 'cornfield'];

        return new NativeSession($options);
    },
    TemplateInterface::class => static function (ContainerInterface $container): TemplateInterface {
        $debug = Constants::ENV_PRODUCTION !== $container->get('environment');

        if ($container->has('template.debug')) {
            $debug = $container->get('template.debug');
        }

        $options = ['auto_reload' => $debug, 'debug' => $debug];

        if (false === $debug && $container->has('template.path.cache')) {
            $options += ['cache' => $container->get('template.path.cache') ?? false];
        }

        if (false === $container->has('template.path.views')) {
            throw new InvalidParameterException('The key "template.path.views" is undefined');
        }

        $extensions = [];

        if ($container->has('template.twig.default.extensions')) {
            array_push($extensions, ...$container->get('template.twig.default.extensions'));
        }

        if ($container->has('template.twig.extensions')) {
            $extensions[] = new UniversalExtension($container->get('template.twig.extensions'));
        }

        return new TwigTemplate($container->get('template.path.views'), $options, $extensions);
    },
];
