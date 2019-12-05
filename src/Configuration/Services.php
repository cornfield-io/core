<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\I18n\I18nInterface;
use Cornfield\Core\I18n\JsonI18n;
use Cornfield\Core\Session\NativeSession;
use Cornfield\Core\Session\SessionInterface;
use Cornfield\Core\Template\TemplateInterface;
use Cornfield\Core\Template\TwigExtension\UniversalExtension;
use Cornfield\Core\Template\TwigTemplate;
use Psr\Container\ContainerInterface;
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
        $options = $container->has('session.options') ? $container->get('session.options') : [];
        $options += ['name' => 'cornfield'];

        return new NativeSession($options);
    },
    TemplateInterface::class => static function (ContainerInterface $container): TemplateInterface {
        if (false === $container->has('template.path.views') || null === $container->get('template.path.views')) {
            throw new InvalidParameterException('The key "template.path.views" is undefined');
        }

        $debug = $container->has('template.debug') ? $container->get('template.debug') : 'prod' === $container->get('app.env');
        $options = ['auto_reload' => $debug, 'debug' => $debug];

        $cache = $container->has('template.cache') ? $container->get('template.cache') : true;
        if (false === $debug && true === $cache && null !== $container->get('path.cache')) {
            $options['cache'] = $container->get('path.cache');
        }

        $extensions = [...$container->get('template.extensions.default')];

        if ($container->has('template.extensions')) {
            $extensions[] = new UniversalExtension($container->get('template.extensions'));
        }

        return new TwigTemplate($container->get('template.path.views'), $options, $extensions);
    },
];
