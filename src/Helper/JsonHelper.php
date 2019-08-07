<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

use Cornfield\Core\Exception\JsonException;
use Exception;

final class JsonHelper
{
    private const JSON_DEPTH = 512;

    /**
     * @param string $json
     * @param bool   $assoc
     * @param int    $depth
     * @param int    $options
     *
     * @return mixed
     *
     * @throws JsonException
     */
    public static function decode(string $json, bool $assoc = false, int $depth = self::JSON_DEPTH, int $options = 0)
    {
        try {
            return json_decode($json, $assoc, $depth, $options | JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            throw new JsonException('Failure to decode data', 0, $exception);
        }
    }

    /**
     * @param mixed $value
     * @param int   $options
     * @param int   $depth
     *
     * @return string
     *
     * @throws JsonException
     */
    public static function encode($value, int $options = 0, int $depth = self::JSON_DEPTH): string
    {
        try {
            return (string) json_encode(func_get_args(), $options | JSON_THROW_ON_ERROR, $depth);
        } catch (Exception $exception) {
            throw new JsonException('Failure to encode data', 0, $exception);
        }
    }
}
