<?php

declare(strict_types=1);

namespace Cornfield\Core\Template;

use Cornfield\Core\Exception\TemplateException;
use Exception;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

final class TwigTemplate implements TemplateInterface
{
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * TwigTemplate constructor.
     *
     * @param string|string[]      $paths
     * @param array<string, mixed> $options
     * @param AbstractExtension[]  $extensions
     *
     * @throws TemplateException
     */
    public function __construct($paths, array $options = [], array $extensions = [])
    {
        try {
            $loader = new FilesystemLoader();

            if (false === is_array($paths)) {
                $paths = ['__main__' => $paths];
            }

            foreach ($paths as $namespace => $path) {
                if ('__main__' === $namespace) {
                    $loader->addPath($path);
                    continue;
                }

                $loader->addPath($path, (string) $namespace);
            }

            $this->twig = new Environment($loader, $options);

            if ($this->twig->isDebug()) {
                $this->twig->addExtension(new DebugExtension());
            }

            $this->twig->setExtensions($extensions);
        } catch (Exception $exception) {
            throw new TemplateException('An error occurs during the loading of template manager', 0, $exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $view, array $parameters = []): string
    {
        try {
            return $this->twig->render($view.'.html.twig', $parameters);
        } catch (Exception $exception) {
            throw new TemplateException('An error occurs during the loading of a template', 0, $exception);
        }
    }
}
