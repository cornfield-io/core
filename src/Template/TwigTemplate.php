<?php

declare(strict_types=1);

namespace Cornfield\Core\Template;

use Cornfield\Core\Exception\TemplateException;
use Exception;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;

final class TwigTemplate implements TemplateInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * TwigTemplate constructor.
     *
     * @param string|string[]      $path
     * @param array                $options
     * @param ExtensionInterface[] $extensions
     * @param array|null           $template
     *
     * @throws TemplateException
     */
    public function __construct($path, array $options = [], array $extensions = [], ?array $template = null)
    {
        try {
            $loader = new FilesystemLoader($path);

            if (null !== $template) {
                foreach ($template as $namespace => $directory) {
                    $loader->addPath($directory, $namespace);
                }
            }

            $this->twig = new Environment($loader, $options);

            if ($this->twig->isDebug()) {
                $this->twig->addExtension(new DebugExtension());
            }

            foreach ($extensions as $extension) {
                $this->twig->addExtension($extension);
            }
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
