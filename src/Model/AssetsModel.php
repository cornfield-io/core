<?php

declare(strict_types=1);

namespace Cornfield\Core\Model;

use Cornfield\Core\Entity\AssetEntity;
use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\Helper\FilesystemHelper;
use DateInterval;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class AssetsModel extends AbstractModel
{
    /**
     * @var string
     */
    private $validity;

    /**
     * AssetsModel constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->validity = $container->has('assets.cache.calidity') ? $container->get('assets.cache.calidity') : '1 day';
    }

    /**
     * @param string $filename
     *
     * @return AssetEntity
     *
     * @throws InvalidParameterException
     * @throws InvalidArgumentException
     */
    public function get(string $filename): AssetEntity
    {
        $cache = $this->container->get(CacheInterface::class);
        $key = 'assets.'.hash('sha256', $filename);

        if (false === $cache->has($key)) {
            $content = FilesystemHelper::getFileContent($filename, null);
            $definition = [
                'content' => $content,
                'version' => FilesystemHelper::getFiletime($filename, null),
                'integrity' => $integrity = 'sha512-'.base64_encode(hash('sha512', $content, true)),
            ];

            $cache->set($key, $definition, DateInterval::createFromDateString($this->validity));

            return new AssetEntity($definition['content'], $definition['version'], $definition['integrity']);
        }

        $definition = $cache->get($key);

        return new AssetEntity($definition['content'], $definition['version'], $definition['integrity']);
    }
}
