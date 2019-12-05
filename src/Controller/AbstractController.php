<?php

declare(strict_types=1);

namespace Cornfield\Core\Controller;

use Cornfield\Core\Exception\TemplateException;
use Cornfield\Core\Http\Response\HtmlResponse;
use Cornfield\Core\Http\Response\JsonResponse;
use Cornfield\Core\Template\TemplateInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractController
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

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
     * @throws TemplateException
     */
    protected function html(ResponseInterface $response, string $view, array $data = [], int $status = 200): ResponseInterface
    {
        return HtmlResponse::from(
            $response,
            $this->container->get(TemplateInterface::class)->render($view, $data),
            $status,
            (string) $this->container->get('charset')
        );
    }
}
