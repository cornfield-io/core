<?php

declare(strict_types=1);

namespace Cornfield\Core\I18n;

use Cornfield\Core\Exception\I18nException;
use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\Helper\FilesystemHelper;
use Cornfield\Core\Helper\JsonHelper;
use Exception;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class JsonI18n implements I18nInterface
{
    /**
     * @var array
     */
    private array $translation = [];

    /**
     * JsonI18n constructor.
     *
     * @param CacheInterface $cache
     * @param string         $default
     * @param string         $callback
     *
     * @throws I18nException
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
     * @return void
     *
     * @throws I18nException
     */
    private function load(CacheInterface $cache, string $default, string $callback): void
    {
        try {
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

                $this->translation += JsonHelper::decode($content, true);
            }

            $cache->set($key, $this->translation);
        } catch (InvalidArgumentException $exception) {
            throw new I18nException('Impossible to load translations', 0, $exception);
        } catch (Exception $exception) {
            throw new I18nException('Impossible to load translations', 0, $exception);
        }
    }
}
