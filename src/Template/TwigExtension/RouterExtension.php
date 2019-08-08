<?php

declare(strict_types=1);

namespace Cornfield\Core\Template\TwigExtension;

use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RouterExtension extends AbstractExtension
{
    /**
     * @var RouteParserInterface
     */
    private $router;

    /**
     * RouterExtension constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->router = $app->getRouteCollector()->getRouteParser();
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
