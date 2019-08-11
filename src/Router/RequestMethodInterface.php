<?php

declare(strict_types=1);

namespace Cornfield\Core\Router;

interface RequestMethodInterface
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_OPTIONS = 'OPTIONS';

    public const METHODS = [
        self::METHOD_GET,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_DELETE,
        self::METHOD_HEAD,
        self::METHOD_PATCH,
        self::METHOD_OPTIONS,
    ];
}
