<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

use Cornfield\Core\Exception\HelperException;
use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\Exception\JsonException;
use Exception;
use ParagonIE\HiddenString\HiddenString;

final class StringHelper
{
    /**
     * @param string $str
     *
     * @return bool
     */
    public static function empty(string $str): bool
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

    /**
     * @param int    $length
     * @param string $alphabet
     *
     * @return string
     *
     * @throws HelperException
     */
    public static function random(int $length = 50, $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZabcdefghijklmnopqrstuvwxyz'): string
    {
        try {
            if ($length < 1) {
                throw new InvalidParameterException('Length must be a positive integer');
            }

            $str = '';
            $alphamax = self::length($alphabet) - 1;

            if ($alphamax < 1) {
                throw new InvalidParameterException('Alphabet expected a string that contains at least 2 distinct characters');
            }

            for ($i = 0; $i < $length; ++$i) {
                $str .= $alphabet[random_int(0, $alphamax)];
            }

            return $str;
        } catch (Exception $exception) {
            throw new HelperException('Impossible to generate random string', 0, $exception);
        }
    }

    /**
     * @param HiddenString $key
     * @param mixed        $data
     *
     * @return string
     *
     * @throws JsonException
     */
    public static function signed(HiddenString $key, $data): string
    {
        return hash_hmac('sha512', JsonHelper::encode($data), $key->getString());
    }
}
