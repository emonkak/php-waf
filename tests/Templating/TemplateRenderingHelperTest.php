<?php

namespace Emonkak\Framework\Tests\Templating;

class TemplateRenderingHelperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->templateEngine = $this->getMock('Symfony\Component\Templating\EngineInterface');
        $this->controller = $this->getMockForTrait('Emonkak\Framework\Templating\TemplateRenderingHelper');
        $this->controller->setTemplateEngine($this->templateEngine);
    }

    public function testRender()
    {
        $name = 'path/to/template.php';
        $parameters = ['foo' => 'bar'];
        $expected = 'hello world';

        $this->templateEngine
            ->expects($this->once())
            ->method('render')
            ->with($this->identicalTo($name), $this->identicalTo($parameters))
            ->willReturn($expected);

        $this->assertSame($expected, $this->controller->render($name, $parameters));
    }

    public function testRenderResponse()
    {
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
    }
}
