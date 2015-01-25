<?php

namespace Emonkak\Framework\Templating;

use Emonkak\Di\Annotations\Inject;
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
