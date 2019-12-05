<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use Cornfield\Core\Middleware\ReduceHtmlOutputMiddleware;
use Cornfield\Core\Middleware\TrailingSlashMiddleware;
use Cornfield\Core\Template\TwigExtension\I18nExtension;
use Cornfield\Core\Template\TwigExtension\RouterExtension;
use Psr\Container\ContainerInterface;

return [
    'router.cache' => static function (ContainerInterface $container): bool {
        return 'prod' === $container->get('app.env');
    },
    'router.middlewares.default' => static function (ContainerInterface $container): array {
        return [
            $container->get(ReduceHtmlOutputMiddleware::class),
            $container->get(TrailingSlashMiddleware::class),
        ];
    },
    'template.extensions.default' => static function (ContainerInterface $container): array {
        return [
            $container->get(I18nExtension::class),
            $container->get(RouterExtension::class),
        ];
    },
];
