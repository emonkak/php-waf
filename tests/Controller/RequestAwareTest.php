<?php

namespace Emonkak\Framework\Tests\Controller;

use Emonkak\Framework\Controller\RequestAware;
use Symfony\Component\HttpFoundation\Request;

class RequestAwareTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->controller = $this->getMockForTrait('Emonkak\Framework\Controller\RequestAware');
    }

    public function testSetTemplateEngine()
    {
        \Closure::bind(function() {
            $request = new Request();
            $this->controller->setRequest($request);
            $this->assertSame($request, $this->controller->request);
        }, $this, get_class($this->controller))->__invoke();
    }
}
