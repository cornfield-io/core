<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

use Slim\Interfaces\RouteGroupInterface as SlimRouteGroupInterface;

final class RouteGroup implements RouteGroupInterface
{
    /**
     * @var SlimRouteGroupInterface
     */
    private $group;

    /**
     * RouteGroup constructor.
     *
     * @param SlimRouteGroupInterface $group
     */
    public function __construct(SlimRouteGroupInterface $group)
    {
        $this->group = $group;
    }
}
