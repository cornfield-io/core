<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

use Cornfield\Core\Exception\InvalidParameterException;

final class FilesystemHelper
{
    /**
     * @param string $pathname
     *
     * @return bool
     */
    public static function isDirWritable(string $pathname): bool
    {
        return is_dir($pathname) && is_writable($pathname);
    }

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
     * @param string $pathname
     * @param int    $mode
     * @param bool   $recursive
     *
     * @return bool
     */
    public static function mkdir(string $pathname, int $mode = 0777, bool $recursive = true): bool
    {
        if (is_dir($pathname)) {
            return true;
        }

        return mkdir($pathname, $mode, $recursive);
    }

    /**
     * @param string $pathname
     *
     * @return string
     */
    public static function path(string $pathname): string
    {
        return rtrim(trim($pathname), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}
