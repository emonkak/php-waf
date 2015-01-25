<?php

namespace Emonkak\Framework\Tests\Templating;

class TemplateEngineAwareTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->templateEngineAware = $this->getMockForTrait('Emonkak\Framework\Templating\TemplateEngineAware');
    }

    public function testSetTemplateEngine()
    {
        \Closure::bind(function() {
            $templateEngine = $this->getMock('Symfony\Component\Templating\EngineInterface');
            $this->templateEngineAware->setTemplateEngine($templateEngine);
            $this->assertSame($templateEngine, $this->templateEngineAware->templateEngine);
        }, $this, get_class($this->templateEngineAware))->__invoke();
    }
}
