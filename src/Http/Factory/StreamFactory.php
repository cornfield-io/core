<?php

declare(strict_types=1);

namespace Cornfield\Core\Http\Factory;

use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Factory\Psr17\NyholmPsr17Factory;

final class StreamFactory implements StreamFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return NyholmPsr17Factory::getStreamFactory()->createStream($content);
    }

    /**
     * {@inheritdoc}
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return NyholmPsr17Factory::getStreamFactory()->createStreamFromFile($filename, $mode);
    }

    /**
     * {@inheritdoc}
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return NyholmPsr17Factory::getStreamFactory()->createStreamFromResource($resource);
    }
}
