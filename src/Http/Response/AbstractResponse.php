<?php

declare(strict_types=1);

namespace Cornfield\Core\Response;

use Cornfield\Core\Exception\ResponseException;
use Cornfield\Core\Http\Factory\StreamFactory;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Factory\Psr17\NyholmPsr17Factory;

abstract class AbstractResponse
{
    /**
     * @return ResponseInterface
     */
    protected static function getResponse(): ResponseInterface
    {
        return NyholmPsr17Factory::getResponseFactory()->createResponse();
    }

    /**
     * @param string|StreamInterface $content
     *
     * @return StreamInterface
     *
     * @throws ResponseException
     */
    protected static function getStream($content): StreamInterface
    {
        try {
            if ($content instanceof StreamInterface) {
                return $content;
            }

            return (new StreamFactory())->createStream($content);
        } catch (Exception $exception) {
            throw new ResponseException('Content must be a string or StreamInterface', 0, $exception);
        }
    }
}
