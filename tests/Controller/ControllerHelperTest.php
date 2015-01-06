<?php

namespace Emonkak\Framework\Tests\Controller;

use Emonkak\Framework\Controller\ControllerHelper;

class ControllerHelperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->controller = $this->getMockForTrait('Emonkak\Framework\Controller\ControllerHelper');
    }

    public function testHtml()
    {
        \Closure::bind(function() {
            $response = $this->controller->html('content');
            $this->assertSame('content', $response->getContent());
            $this->assertSame('text/html', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testText()
    {
        \Closure::bind(function() {
            $response = $this->controller->text('content');
            $this->assertSame('content', $response->getContent());
            $this->assertSame('text/plain', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testXml()
    {
        \Closure::bind(function() {
            $response = $this->controller->xml('content');
            $this->assertSame('content', $response->getContent());
            $this->assertSame('application/xml', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testJson()
    {
        \Closure::bind(function() {
            $response = $this->controller->json(['content' => 'content']);
            $this->assertSame('{"content":"content"}', $response->getContent());
            $this->assertSame('application/json', $response->headers->get('Content-Type'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testRedirect()
    {
        \Closure::bind(function() {
            $response = $this->controller->redirect('/path/to/redirect/');
            $this->assertTrue($response->isRedirection());
            $this->assertSame('/path/to/redirect/', $response->headers->get('Location'));
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testCreateBadRequestException()
    {
        \Closure::bind(function() {
            $exception = $this->controller->createBadRequestException('bad request');
            $this->assertInstanceOf('Emonkak\Framework\Exception\HttpBadRequestException', $exception);
            $this->assertSame('bad request', $exception->getMessage());
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testCreateForbiddenException()
    {
        \Closure::bind(function() {
            $exception = $this->controller->createForbiddenException('forbidden');
            $this->assertInstanceOf('Emonkak\Framework\Exception\HttpForbiddenException', $exception);
            $this->assertSame('forbidden', $exception->getMessage());
        }, $this, get_class($this->controller))->__invoke();
    }

    public function testCreateNotFoundException()
    {
        \Closure::bind(function() {
            $exception = $this->controller->createNotFoundException('not found');
            $this->assertInstanceOf('Emonkak\Framework\Exception\HttpNotFoundException', $exception);
            $this->assertSame('not found', $exception->getMessage());
        }, $this, get_class($this->controller))->__invoke();
    }
}
