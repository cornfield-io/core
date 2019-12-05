<?php

declare(strict_types=1);

namespace Cornfield\Core\Http\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class HtmlResponse extends AbstractResponse
{
    /**
     * @param ResponseInterface      $response
     * @param string|StreamInterface $body
     * @param int                    $status
     * @param string                 $charset
     *
     * @return ResponseInterface
     */
    public static function from(ResponseInterface $response, $body, int $status = 200, string $charset = 'utf-8'): ResponseInterface
    {
        return $response
            ->withHeader('Content-Type', 'text/html; charset='.$charset)
            ->withStatus($status)
            ->withBody(self::getStream($body));
    }

    /**
     * @param string|StreamInterface $body
     * @param int                    $status
     * @param string                 $charset
     *
     * @return ResponseInterface
     */
    public static function fromScratch($body, int $status = 200, string $charset = 'utf-8'): ResponseInterface
    {
        return self::from(self::getResponse(), $body, $status, $charset);
    }
}
