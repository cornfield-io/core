<?php

declare(strict_types=1);

namespace Cornfield\Core\Http\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class RedirectResponse extends AbstractResponse
{
    /**
     * @param string|UriInterface $uri
     * @param int                 $status
     *
     * @return ResponseInterface
     */
    public static function fromScratch($uri, int $status = 302): ResponseInterface
    {
        return self::getResponse()->withStatus($status)->withHeader('Location', (string) $uri);
    }
}
