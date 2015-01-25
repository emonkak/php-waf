<?php

namespace Emonkak\Framework\Templating;

use Emonkak\Di\Annotations\Inject;
use Symfony\Component\Templating\EngineInterface;

/**
 * This should be implemented by classes that depends on a template engine.
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
