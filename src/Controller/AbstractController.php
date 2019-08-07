<?php

declare(strict_types=1);

namespace Cornfield\Core\Controller;

use Cornfield\Core\Exception\ResponseException;
use Cornfield\Core\Exception\TemplateException;
use Cornfield\Core\Response\HtmlResponse;
use Cornfield\Core\Response\JsonResponse;
use Cornfield\Core\Template\TemplateInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

abstract class AbstractController
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AbstractController constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ResponseInterface $response
     * @param string            $json
     * @param int               $status
     *
     * @return ResponseInterface
     *
     * @throws ResponseException
     */
    protected function json(ResponseInterface $response, string $json, int $status = 200): ResponseInterface
    {
        return JsonResponse::from($response, $json, $status);
    }

    /**
     * @param ResponseInterface $response
     * @param string            $view
     * @param array             $data
     * @param int               $status
     *
     * @return ResponseInterface
     *
     * @throws ResponseException
     * @throws TemplateException
     */
    protected function html(ResponseInterface $response, string $view, array $data = [], int $status = 200): ResponseInterface
    {
        return HtmlResponse::from(
            $response,
            $this->container->get(TemplateInterface::class)->render($view, $data),
            $status,
            $this->container->has('charset') ? $this->container->get('charset') : 'utf-8'
        );
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function urlFor(string $name): string
    {
        return $this->container->get(App::class)->getRouteCollector()->getRouteParser()->urlFor($name);
    }
}
