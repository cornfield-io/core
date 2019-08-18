<?php

declare(strict_types=1);

namespace Cornfield\Core\Http\Factory;

use Cornfield\Core\Router\RequestMethodInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

final class RequestFactory implements RequestFactoryInterface, RequestMethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return (new Psr17Factory())->createRequest($method, $uri);
    }
}
