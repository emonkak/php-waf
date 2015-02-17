<?php

namespace Emonkak\Waf\Tests\Controller;

use Emonkak\Waf\Controller\ControllerHelper;

class ControllerHelperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->controller = $this->getMockForTrait('Emonkak\Waf\Controller\ControllerHelper');
    }

    public function testHtml()
    {
        \Closure::bind(function() {
            $response = $this->controller->html('content');
            $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
            $this->assertSame('content', $response->getContent());
            $this->assertSame('text/html', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testText()
    {
        \Closure::bind(function() {
            $response = $this->controller->text('content');
            $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
            $this->assertSame('content', $response->getContent());
            $this->assertSame('text/plain', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testXml()
    {
        \Closure::bind(function() {
            $response = $this->controller->xml('content');
            $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
            $this->assertSame('content', $response->getContent());
            $this->assertSame('application/xml', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testJson()
    {
        \Closure::bind(function() {
            $response = $this->controller->json(['content' => 'content']);
            $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
            $this->assertSame('{"content":"content"}', $response->getContent());
            $this->assertSame('application/json', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testRedirect()
    {
        \Closure::bind(function() {
            $response = $this->controller->redirect('/path/to/redirect/');
            $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
            $this->assertTrue($response->isRedirection());
            $this->assertSame(302, $response->getStatusCode());
            $this->assertSame('/path/to/redirect/', $response->headers->get('Location'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testStream()
    {
        \Closure::bind(function() {
            $callback = $this->getMock('stdClass', ['__invoke']);
            $callback
                ->expects($this->once())
                ->method('__invoke');

            $response = $this->controller->stream($callback);
            $response->sendContent();

            $this->assertInstanceOf('Symfony\Component\HttpFoundation\StreamedResponse', $response);
        }, $this, get_class($this->controller))->__invoke();
    }
}
