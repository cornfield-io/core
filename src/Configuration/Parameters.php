<?php

declare(strict_types=1);

namespace Cornfield\Core\Configuration;

use Cornfield\Core\Template\TwigExtension\RouterExtension;
use Psr\Container\ContainerInterface;

return [
    'template.twig.default.extensions' => static function (ContainerInterface $container): array {
        return [
            $container->get(RouterExtension::class),
        ];
    },
];
