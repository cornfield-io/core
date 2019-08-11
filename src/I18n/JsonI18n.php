<?php

declare(strict_types=1);

namespace Cornfield\Core\I18n;

use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\Exception\JsonException;
use Cornfield\Core\Helper\FilesystemHelper;
use Cornfield\Core\Helper\JsonHelper;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class JsonI18n implements I18nInterface
{
    /**
     * @var array
     */
    private $translation = [];

    /**
     * JsonI18n constructor.
     *
     * @param CacheInterface $cache
     * @param string         $default
     * @param string         $callback
     *
     * @throws InvalidArgumentException
     * @throws InvalidParameterException
     * @throws JsonException
     */
    public function __construct(CacheInterface $cache, string $default, string $callback = '')
    {
        $this->load($cache, $default, $callback);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key): string
    {
        if (false === isset($this->translation[$key])) {
            throw new InvalidParameterException('Language key "'.$key.'" is undefined!');
        }

        return $this->translation[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return isset($this->translation[$key]);
    }

    /**
     * @param CacheInterface $cache
     * @param string         $default
     * @param string         $callback
     *
     * @throws InvalidArgumentException
     * @throws InvalidParameterException
     * @throws JsonException
     */
    private function load(CacheInterface $cache, string $default, string $callback): void
    {
        $key = 'i18n.'.hash('sha256', $default.$callback);

        if ($cache->has($key)) {
            $this->translation = $cache->get($key);

            return;
        }

        foreach ([$default => null, $callback => ''] as $file => $present) {
            $content = FilesystemHelper::getFileContent($file, $present);

            if ('' === $content) {
                continue;
            }

            $this->translation += JsonHelper::decode($content);
        }

        $cache->set($key, $this->translation);
    }
}
