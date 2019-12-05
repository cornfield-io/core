<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

final class StringHelper
{
    /**
     * @param string $str
     *
     * @return bool
     */
    public static function isEmpty(string $str): bool
    {
        return self::length($str) < 1;
    }

    /**
     * @param string $str
     *
     * @return string
     */
    public static function escape(string $str): string
    {
        return htmlentities($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * @param string $str
     *
     * @return int
     */
    public static function length(string $str): int
    {
        return mb_strlen($str, '8bit');
    }
}
