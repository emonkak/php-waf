<?php

namespace Emonkak\Framework\Tests;

use Emonkak\Di\Container;
use Emonkak\Framework\AbstractApplication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AbstractApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testBoot()
    {
        \Closure::bind(function() {
            $config = ['foo' => 'bar'];
            $container = Container::create();

            $application = $this->getMockBuilder('Emonkak\Framework\AbstractApplication')
                ->disableOriginalConstructor()
                ->setMethods(['doBoot', 'prepareContainer'])
                ->getMock();
            $application
                ->expects($this->once())
                ->method('doBoot');
            $application
                ->expects($this->once())
                ->method('prepareContainer')
                ->willReturn($container);

            $application->__construct($config);

            $this->assertSame($config, $application->config);
            $this->assertSame($container, $application->container);

            $application->boot();
        }, $this, 'Emonkak\Framework\AbstractApplication')->__invoke();
    }

    public function testHandle()
    {
        $request = new Request();
        $response = new Response();
        $session = new Session();

        $kernel = $this->getMock('Emonkak\Framework\KernelInterface');
        $kernel
            ->expects($this->any())
            ->method('handleRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $container = Container::create();
        $container->set('Symfony\Component\HttpFoundation\Session\SessionInterface', $session);
        $container->set('Emonkak\Framework\KernelInterface', $kernel);

        $application = $this->getMockBuilder('Emonkak\Framework\AbstractApplication')
           ->disableOriginalConstructor()
           ->setMethods(['doBoot', 'prepareContainer'])
           ->getMock();
        $application
            ->expects($this->once())
            ->method('doBoot');
        $application
            ->expects($this->once())
            ->method('prepareContainer')
            ->willReturn($container);

        $application->__construct();

        $this->assertSame($response, $application->handle($request));
        $this->assertSame($response, $application->handle($request));
        $this->assertSame($session, $request->getSession());
    }
}
