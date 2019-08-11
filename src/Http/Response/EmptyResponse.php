<?php

declare(strict_types=1);

namespace Cornfield\Core\Http\Response;

use Cornfield\Core\Exception\ResponseException;
use Cornfield\Core\Response\AbstractResponse;
use Psr\Http\Message\ResponseInterface;

final class EmptyResponse extends AbstractResponse
{
    /**
     * @param ResponseInterface $response
     * @param int               $status
     *
     * @return ResponseInterface
     *
     * @throws ResponseException
     */
    public static function from(ResponseInterface $response, int $status = 200): ResponseInterface
    {
        return $response
            ->withStatus($status)
            ->withBody(self::getStream(''));
    }

    /**
     * @param int $status
     *
     * @return ResponseInterface
     *
     * @throws ResponseException
     */
    public static function fromScratch(int $status = 200): ResponseInterface
    {
        return self::from(self::getResponse(), $status);
    }
}
