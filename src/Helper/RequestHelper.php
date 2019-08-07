<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

final class RequestHelper
{
    /**
     * @return bool
     */
    public static function isHttps(): bool
    {
        static $https;

        if (null === $https) {
            $https = false;

            if (isset($_SERVER['HTTPS']) && ('1' === $_SERVER['HTTPS'] || 'on' === mb_strtolower($_SERVER['HTTPS']))) {
                $https = true;
            }

            if (isset($_SERVER['SERVER_PORT']) && '443' === $_SERVER['SERVER_PORT']) {
                $https = true;
            }
        }

        return $https;
    }
}
