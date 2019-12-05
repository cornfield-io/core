<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

use Cornfield\Core\Exception\InvalidParameterException;

final class SystemHelper
{
    /**
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed|null
     *
     * @throws InvalidParameterException
     */
    public static function env(string $name, $default = null)
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }

        if (2 === func_num_args()) {
            return $default;
        }

        throw new InvalidParameterException(sprintf('The environment variable "%s" has not been defined', $name));
    }
}
