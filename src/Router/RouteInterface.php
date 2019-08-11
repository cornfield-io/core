<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

interface RouteInterface
{
    /**
     * @param string $name
     *
     * @return RouteInterface
     */
    public function setName(string $name): RouteInterface;
}
