<?php

declare(strict_types=1);

namespace Cornfield\Core\Middleware;

use Cornfield\Core\Http\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TrailingSlashMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ('/' !== $path && '/' === $path[-1]) {
            $uri = $uri->withPath(rtrim($path, '/'));

            if ('get' === mb_strtolower($request->getMethod())) {
                return RedirectResponse::fromScratch($uri, 301);
            }

            return $handler->handle($request->withUri($uri));
        }

        return $handler->handle($request);
    }
}
