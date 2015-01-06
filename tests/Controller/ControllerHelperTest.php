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
        $response = $this->controller->html('content');
        $this->assertSame('content', $response->getContent());
        $this->assertSame('text/html', $response->headers->get('Content-Type'));
    }

    public function testText()
    {
        $response = $this->controller->text('content');
        $this->assertSame('content', $response->getContent());
        $this->assertSame('text/plain', $response->headers->get('Content-Type'));
    }

    public function testXml()
    {
        $response = $this->controller->xml('content');
        $this->assertSame('content', $response->getContent());
        $this->assertSame('application/xml', $response->headers->get('Content-Type'));
    }

    public function testJson()
    {
        $response = $this->controller->json(['content' => 'content']);
        $this->assertSame('{"content":"content"}', $response->getContent());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
    }

    public function testRedirect()
    {
        $response = $this->controller->redirect('/path/to/redirect/');
        $this->assertTrue($response->isRedirection());
        $this->assertSame('/path/to/redirect/', $response->headers->get('Location'));
    }

    public function testCreateBadRequestException()
    {
        $exception = $this->controller->createBadRequestException('bad request');
        $this->assertInstanceOf('Emonkak\Framework\Exception\HttpBadRequestException', $exception);
        $this->assertSame('bad request', $exception->getMessage());
    }

    public function testCreateForbiddenException()
    {
        $exception = $this->controller->createForbiddenException('forbidden');
        $this->assertInstanceOf('Emonkak\Framework\Exception\HttpForbiddenException', $exception);
        $this->assertSame('forbidden', $exception->getMessage());
    }

    public function testCreateNotFoundException()
    {
        $exception = $this->controller->createNotFoundException('not found');
        $this->assertInstanceOf('Emonkak\Framework\Exception\HttpNotFoundException', $exception);
        $this->assertSame('not found', $exception->getMessage());
    }
}
