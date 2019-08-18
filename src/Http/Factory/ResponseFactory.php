<?php

declare(strict_types=1);

namespace Cornfield\Core\Http\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\Psr17\NyholmPsr17Factory;

final class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return NyholmPsr17Factory::getResponseFactory()->createResponse($code, $reasonPhrase);
    }
}
