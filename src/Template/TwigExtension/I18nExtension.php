<?php

declare(strict_types=1);

namespace Cornfield\Core\Template\TwigExtension;

use Cornfield\Core\Exception\InvalidParameterException;
use Cornfield\Core\I18n\I18nInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class I18nExtension extends AbstractExtension
{
    /**
     * @var I18nInterface
     */
    private I18nInterface $i18n;

    /**
     * I18nExtension constructor.
     *
     * @param I18nInterface $i18n
     */
    public function __construct(I18nInterface $i18n)
    {
        $this->i18n = $i18n;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('i18n', [$this, 'i18n']),
        ];
    }

    /**
     * @param string|mixed[] $key
     * @param string         $default
     *
     * @return string
     *
     * @throws InvalidParameterException
     */
    public function i18n($key, string $default = ''): string
    {
        if (is_array($key)) {
            if (is_string($key[0]) && $this->i18n->has($key[0])) {
                return sprintf($this->i18n->get($key[0]), ...array_slice($key, 1));
            }

            return $default;
        }

        if (false === $this->i18n->has($key)) {
            return $default;
        }

        return $this->i18n->get($key);
    }
}
