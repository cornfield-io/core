<?php

declare(strict_types=1);

namespace Cornfield\Core\Middleware;

use Cornfield\Core\Http\Factory\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ReduceHtmlOutputMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (false !== mb_strstr($response->getHeaderLine('content-type'), 'text/html')) {
            @ini_set('pcre.recursion_limit', '16777');
            $regex = '%# Collapse whitespace everywhere but in blacklisted elements.
            (?>             # Match all whitespans other than single space.
              [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
            | \s{2,}        # or two or more consecutive-any-whitespace.
            ) # Note: The remaining regex consumes no text at all...
            (?=             # Ensure we are not in a blacklist tag.
              [^<]*+        # Either zero or more non-"<" {normal*}
              (?:           # Begin {(special normal*)*} construct
                <           # or a < starting a non-blacklist tag.
                (?!/?(?:textarea|pre|script)\b)
                [^<]*+      # more non-"<" {normal*}
              )*+           # Finish "unrolling-the-loop"
              (?:           # Begin alternation group.
                <           # Either a blacklist start tag.
                (?>textarea|pre|script)\b
              | \z          # or end of file.
              )             # End alternation group.
            )  # If we made it here, we are not in a blacklist tag.
            %Six';

            $content = $response->getBody()->getContents();

            return $response->withBody((new StreamFactory())->createStream(preg_replace($regex, '', $content) ?? $content));
        }

        return $response;
    }
}
