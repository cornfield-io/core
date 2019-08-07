<?php

declare(strict_types=1);

namespace Cornfield\Core\Template;

use Cornfield\Core\Exception\TemplateException;

interface TemplateInterface
{
    /**
     * @param string $view
     * @param array  $parameters
     *
     * @return string
     *
     * @throws TemplateException
     */
    public function render(string $view, array $parameters = []): string;
}
