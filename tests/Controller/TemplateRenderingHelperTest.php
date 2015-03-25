<?php

namespace Emonkak\Waf\Tests\Controller;

class TemplateRenderingHelperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->templateEngine = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $this->controller = $this->getMockForTrait('Emonkak\Waf\Controller\TemplateRenderingHelper');
        $this->controller->setTemplateEngine($this->templateEngine);
    }

    public function testSetTemplateEngine()
    {
        \Closure::bind(function() {
            $templateEngine = $this->getMock('Symfony\Component\Templating\EngineInterface');
            $this->controller->setTemplateEngine($templateEngine);
            $this->assertSame($templateEngine, $this->controller->templateEngine);
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testRender()
    {
        \Closure::bind(function() {
            $name = 'path/to/template.php';
            $parameters = ['foo' => 'bar'];
            $expected = 'hello world';

            $this->templateEngine
                ->expects($this->once())
                ->method('render')
                ->with($this->identicalTo($name), $this->identicalTo($parameters))
                ->willReturn($expected);

            $this->assertSame($expected, $this->controller->render($name, $parameters));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testRenderResponse()
    {
        \Closure::bind(function() {
            $name = 'path/to/template.php';
            $parameters = ['foo' => 'bar'];
            $expected = 'hello world';

            $this->templateEngine
                ->expects($this->once())
                ->method('render')
                ->with($this->identicalTo($name), $this->identicalTo($parameters))
                ->willReturn($expected);

            $response = $this->controller->renderResponse($name, $parameters);

            $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
            $this->assertSame($expected, $response->getContent());
        }, $this, get_class($this->controller))->__invoke();
    }
}
