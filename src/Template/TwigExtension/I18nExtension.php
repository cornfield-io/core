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
    private $i18n;

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
     * @param string      $key
     * @param string|null $default
     *
     * @return string
     *
     * @throws InvalidParameterException
     */
    public function i18n(string $key, ?string $default = ''): string
    {
        if (is_string($default) && false === $this->i18n->has($key)) {
            return $default;
        }

        return $this->i18n->get($key);
    }
}
