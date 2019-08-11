<?php

declare(strict_types=1);

namespace Cornfield\Core\I18n;

use Cornfield\Core\Exception\InvalidParameterException;

interface I18nInterface
{
    /**
     * @param string $key
     *
     * @throws InvalidParameterException
     *
     * @return string
     */
    public function get(string $key): string;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;
}
