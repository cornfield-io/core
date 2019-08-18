<?php

declare(strict_types=1);

namespace Cornfield\Core\Template\TwigExtension;

use Cornfield\Core\Model\RouterModel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RouterExtension extends AbstractExtension
{
    /**
     * @var RouterModel
     */
    private $router;

    /**
     * RouterExtension constructor.
     *
     * @param RouterModel $router
     */
    public function __construct(RouterModel $router)
    {
        $this->router = $router;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'path']),
        ];
    }

    /**
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    public function path(string $path, array $params = []): string
    {
        return $this->router->urlFor($path, $params);
    }
}
