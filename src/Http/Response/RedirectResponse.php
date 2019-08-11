<?php

declare(strict_types=1);

namespace Cornfield\Core\Response;

use Cornfield\Core\Exception\ResponseException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class RedirectResponse extends AbstractResponse
{
    /**
     * @param string|UriInterface $uri
     * @param int                 $status
     *
     * @return ResponseInterface
     *
     * @throws ResponseException
     */
    public static function fromScratch($uri, int $status = 302): ResponseInterface
    {
        try {
            return self::getResponse()->withStatus($status)->withHeader('Location', (string) $uri);
        } catch (Exception $exception) {
            throw new ResponseException('Uri must be a string or UriInterface', 0, $exception);
        }
    }
}
