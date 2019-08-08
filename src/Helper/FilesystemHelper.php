<?php

declare(strict_types=1);

namespace Cornfield\Core\Helper;

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
     * @param string $path
     *
     * @return string
     */
    public static function path(string $path): string
    {
        return rtrim(trim($path), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }
}
