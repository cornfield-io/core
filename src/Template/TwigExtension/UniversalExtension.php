<?php

declare(strict_types=1);

namespace Cornfield\Core\Template\TwigExtension;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class UniversalExtension extends AbstractExtension
{
    /**
     * @var TwigFunction[]
     */
    private array $extensions = [];

    /**
     * UniversalExtension constructor.
     *
     * @param array $extensions
     */
    public function __construct(array $extensions)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        foreach ($extensions as $extension) {
            $options = $resolver->resolve($extension);
            $this->extensions[] = new TwigFunction($options['name'], $options['callable'], $options['options']);
        }
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return $this->extensions;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['options' => []]);

        $resolver->setRequired(['name', 'callable', 'options']);

        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('callable', ['array', 'callable', 'null']);
        $resolver->setAllowedTypes('options', 'array');
    }
}
