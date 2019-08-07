<?php

declare(strict_types=1);

namespace Cornfield\Core\Route;

use Slim\App;

interface RouteInterface
{
    /**
     * @param App $app
     *
     * @return bool
     */
    public static function add(App $app): bool;
}
