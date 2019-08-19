<?php

declare(strict_types=1);

namespace Cornfield\Core\Session;

use Cornfield\Core\Exception\SessionException;
use Cornfield\Core\Helper\RequestHelper;
use DateInterval;
use DateTime;
use Exception;

final class NativeSession implements SessionInterface
{
    private const DATETIME_FORMAT = DateTime::RFC3339;
    private const REGENERATE_NAME = 'parameters.renew.expires';
    private const REGENERATE_INTERVAL = 'PT5M';

    /**
     * @var array
     */
    private $options;

    /**
     * NativeSession constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options + [
                'use_strict_mode' => true,
                'cookie_httponly' => true,
                'cookie_samesite' => 'Strict',
                'cookie_secure' => RequestHelper::isHttps(),
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function start(): bool
    {
        try {
            if (false === session_start($this->options)) {
                return false;
            }

            return $this->regenerate();
        } catch (Exception $exception) {
            session_write_close();
            throw new SessionException('Session is unsuccessfully started', 0, $exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, $default = null)
    {
        if (false === $this->has($key)) {
            return $default;
        }

        return $_SESSION[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value): bool
    {
        $_SESSION[$key] = $value;

        return $this->has($key) && $value === $_SESSION[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $key): bool
    {
        unset($_SESSION[$key]);

        return $this->has($key);
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function clear(): bool
    {
        $_SESSION = [];

        return session_unset() && session_regenerate_id(true) && $this->regenerate(false);
    }

    /**
     * @param bool $clear
     *
     * @return bool
     *
     * @throws Exception
     */
    private function regenerate($clear = true): bool
    {
        $now = new DateTime();
        $expires = $this->get(self::REGENERATE_NAME, '');

        if ('' === $expires || false === is_string($expires)) {
            if (true === $clear) {
                return $this->clear();
            }

            return $this->set(self::REGENERATE_NAME, $now->format(self::DATETIME_FORMAT));
        }

        $dt = (new DateTime($expires))->add(new DateInterval(self::REGENERATE_INTERVAL));

        if ($dt < $now) {
            $this->set(self::REGENERATE_NAME, $now->format(self::DATETIME_FORMAT));

            return session_regenerate_id(true);
        }

        return true;
    }
}
