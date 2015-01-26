<?php

namespace Emonkak\Framework\Tests\Controller;

class TemplateEngineAwareTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->controller = $this->getMockForTrait('Emonkak\Framework\Controller\TemplateEngineAware');
    }

    public function testSetTemplateEngine()
    {
        \Closure::bind(function() {
            $templateEngine = $this->getMock('Symfony\Component\Templating\EngineInterface');
            $this->controller->setTemplateEngine($templateEngine);
            $this->assertSame($templateEngine, $this->controller->templateEngine);
        }, $this, get_class($this->controller))->__invoke();
    }
}
