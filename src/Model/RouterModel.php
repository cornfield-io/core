<?php

declare(strict_types=1);

namespace Cornfield\Core\Model;

use Slim\App;

final class RouterModel extends AbstractModel
{
    /**
     * @param string $name
     * @param array  $data
     *
     * @return string
     */
    public function urlFor(string $name, array $data = []): string
    {
        return $this->container->get(App::class)->getRouteCollector()->getRouteParser()->urlFor($name, $data);
    }
}
