<?php

declare(strict_types=1);

namespace Cornfield\Core\Template\TwigExtension;

use Slim\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RouterExtension extends AbstractExtension
{
    /**
     * @var App
     */
    private App $app;

    /**
     * RouterExtension constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
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
        return $this->app->getRouteCollector()->getRouteParser()->urlFor($path, $params);
    }
}
