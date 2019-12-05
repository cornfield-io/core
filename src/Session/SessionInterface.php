<?php

declare(strict_types=1);

namespace Cornfield\Core\Session;

use Cornfield\Core\Exception\SessionException;

interface SessionInterface
{
    /**
     * @return bool
     *
     * @throws SessionException
     */
    public function start(): bool;

    /**
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     *
     * @throws SessionException
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function set(string $key, $value): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * @return bool
     *
     * @throws SessionException
     */
    public function clear(): bool;
}
