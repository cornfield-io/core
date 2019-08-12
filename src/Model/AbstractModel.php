<?php

declare(strict_types=1);

namespace Cornfield\Core\Model;

use Psr\Container\ContainerInterface;

abstract class AbstractModel
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractModel constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
