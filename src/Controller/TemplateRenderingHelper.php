<?php

namespace Emonkak\Waf\Controller;

use Emonkak\Di\Annotation\Inject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Represents a helper of template rendering.
 */
trait TemplateRenderingHelper
{
    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * Sets the template engine.
     *
     * @Inject
     *
     * @param EngineInterface $templateEngine
     */
    public function setTemplateEngine(EngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    /**
     * Renders a template.
     *
     * @param string $name       A template name
     * @param array  $parameters An array of parameters to pass to the template
     * @return string The evaluated template as a string
     */
    public function render($name, array $parameters = [])
    {
        return $this->templateEngine->render($name, $parameters);
    }

    /**
     * Renders a template and returns a response.
     *
     * @param string $name       A template name
     * @param array  $parameters An array of parameters to pass to the template
     * @return Response The evaluated template as a response
     */
    public function renderResponse($name, array $parameters = [])
    {
        return new Response($this->templateEngine->render($name, $parameters));
    }
}
