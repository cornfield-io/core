<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\Session\NativeSession;
use Cornfield\Core\Session\SessionInterface;
use Cornfield\Core\Template\TemplateInterface;
use Cornfield\Core\Template\TwigTemplate;
use Psr\Container\ContainerInterface;

return [
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
            $options += ['cache' => $container->get('template.path.cache')];
        }

        if (false === $container->has('template.path.views')) {
            throw new InvalidParameterException('The key "template.path.views" is undefined');
        }

        $extensions = [];

        foreach (['template.twig.extensions', 'template.twig.default.extensions'] as $list) {
            if ($container->has($list)) {
                $extensions += (array) $container->get($list);
            }
        }

        return new TwigTemplate($container->get('template.path.views'), $options, $extensions);
    },
];
