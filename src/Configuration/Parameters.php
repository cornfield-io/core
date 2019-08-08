<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use Cornfield\Core\Template\TwigExtension\RouterExtension;
use Psr\Container\ContainerInterface;

return [
    'template.path.cache' => static function (ContainerInterface $container): ?string {
        if ($container->has('path.cache')) {
            return $container->get('path.cache').DIRECTORY_SEPARATOR.'Template';
        }

        return null;
    },
    'template.twig.default.extensions' => static function (ContainerInterface $container): array {
        return [
            $container->get(RouterExtension::class),
        ];
    },
];
