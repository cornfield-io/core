<?php

declare(strict_types=1);

namespace Cornfield\Core\Template\TwigExtension;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class UniversalExtension extends AbstractExtension
{
    /**
     * @var callable|null
     */
    private $callable;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options;

    /**
     * UniversalExtension constructor.
     *
     * @param array $extension
     */
    public function __construct(array $extension)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($extension);

        $this->name = $options['name'];
        $this->callable = $options['callable'];
        $this->options = $options['options'];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction($this->name, $this->callable, $this->options),
        ];
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['options' => []]);
        $resolver->setRequired(['name', 'callable', 'options']);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('callable', ['callable', 'null']);
        $resolver->setAllowedTypes('options', 'array');
    }
}
