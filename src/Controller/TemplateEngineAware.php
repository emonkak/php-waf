<?php

namespace Emonkak\Waf\Controller;

use Emonkak\Di\Annotation\Inject;
use Symfony\Component\Templating\EngineInterface;

/**
 * Provides access to a template engine instance.
 */
trait TemplateEngineAware
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
}
