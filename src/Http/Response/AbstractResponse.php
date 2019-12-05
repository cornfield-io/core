<?php

declare(strict_types=1);

namespace Cornfield\Core\Http\Response;

use Cornfield\Core\Http\Factory\StreamFactory;
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
     */
    protected static function getStream($content): StreamInterface
    {
        if ($content instanceof StreamInterface) {
            return $content;
        }

        return (new StreamFactory())->createStream($content);
    }
}
