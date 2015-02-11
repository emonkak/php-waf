<?php

namespace Emonkak\Waf\Tests\Controller;

use Emonkak\Waf\Controller\RequestAware;

class RequestAwareTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->controller = $this->getMockForTrait('Emonkak\Waf\Controller\RequestAware');
    }

    public function testSetTemplateEngine()
    {
        \Closure::bind(function() {
            $request = $this->getMock('Psr\Http\Message\RequestInterface');
            $this->controller->setRequest($request);
            $this->assertSame($request, $this->controller->request);
        }, $this, get_class($this->controller))->__invoke();
    }
}
