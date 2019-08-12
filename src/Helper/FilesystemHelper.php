<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

use Cornfield\Core\Exception\InvalidParameterException;

final class FilesystemHelper
{
    /**
     * @param string $filename
     *
     * @return bool
     */
    public static function isFileReadable(string $filename): bool
    {
        return is_file($filename) && is_readable($filename);
    }

    /**
     * @param string      $filename
     * @param string|null $default
     *
     * @return string
     *
     * @throws InvalidParameterException
     */
    public static function getFileContent(string $filename, ?string $default = ''): string
    {
        if (false === self::isFileReadable($filename)) {
            if (null === $default) {
                throw new InvalidParameterException('Can not read file: '.$filename);
            }

            return $default;
        }

        $content = file_get_contents($filename);

        if (false === is_string($content)) {
            if (null === $default) {
                throw new InvalidParameterException('Can not read file: '.$filename);
            }

            return $default;
        }

        return $content;
    }

    /**
     * @param string   $filename
     * @param int|null $default
     *
     * @return int
     *
     * @throws InvalidParameterException
     */
    public static function getFiletime(string $filename, ?int $default = 0): int
    {
        if (false === is_file($filename)) {
            if (null === $default) {
                throw new InvalidParameterException('Can not get file modification time');
            }

            return $default;
        }

        $time = filemtime($filename);

        if (false === is_int($time)) {
            if (null === $default) {
                throw new InvalidParameterException('Can not get file modification time');
            }

            return $default;
        }

        return $time;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function path(string $path): string
    {
        return rtrim(trim($path), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}
