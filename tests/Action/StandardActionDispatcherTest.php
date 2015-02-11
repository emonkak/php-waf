<?php

namespace Emonkak\Waf\Tests\Action
{
    use Emonkak\Waf\Action\StandardActionDispatcher;
    use Emonkak\Waf\Routing\MatchedRoute;

    class StandardActionDispatcherTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideDispatch
         */
        public function testDispatch($path, $method, $controllerName, $actionName, array $params, $expectedMethod)
        {
            $request = $this->createRequestMock($path, $method);
            $response = $this->getMock('Psr\Http\Message\ResponseInterface');

            $controllerMock = $this->getMock($controllerName);
            $invocationMock = $controllerMock
                ->expects($actionSpy = $this->once())
                ->method($expectedMethod)
                ->willReturn($response);

            call_user_func_array(
                [$invocationMock, 'with'],
                array_map([$this, 'identicalTo'], $params)
            );

            $match = new MatchedRoute(get_class($controllerMock), $actionName, $params);
            $dispatcher = new StandardActionDispatcher();

            $this->assertSame($response, $dispatcher->dispatch($request, $match, $controllerMock));
            $this->assertCount(1, $actionSpy->getInvocations());
        }

        public function provideDispatch()
        {
            return [
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'index', [], 'indexAction'],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'show', ['123'], 'showAction'],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'edit', ['123'], 'editAction'],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'between', ['123', '456'], 'betweenAction'],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'foo_bar', [], 'fooBarAction'],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpNotFoundException
         * @expectedException Emonkak\Waf\Exception\HttpNotFoundException
         */
        public function testDispatchThrowsHttpNotFoundException($path, $method, $controllerName, $actionName, array $params)
        {
            $request = $this->createRequestMock($path, $method);
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new StandardActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpNotFoundException()
        {
            return [
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'not_found', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'FooBar', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'FOOBAR', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'baz', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'baz', ['123']],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'Edit', ['123']],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'EDIT', ['123']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'Between', ['123', '456']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'BETWEEN', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpBadRequestException
         * @expectedException Emonkak\Waf\Exception\HttpBadRequestException
         */
        public function testDispatchThrowsHttpBadRequestException($path, $method, $controllerName, $actionName, array $params)
        {
            $request = $this->createRequestMock($path, $method);
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new StandardActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpBadRequestException()
        {
            return [
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'index', ['foo']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'show', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'show', ['foo', 'bar']],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'edit', ['123', '456', '789']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'between', ['123', '456', '789']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\StandardActionDispatcherTest\FooController', 'foo_bar', ['foo']],
            ];
        }

        public function testCanDispatch()
        {
            $dispatcher = new StandardActionDispatcher();
            $request = $this->getMock('Psr\Http\Message\RequestInterface');

            $controller = new \StdClass();
            $match = new MatchedRoute('StdClass', 'action', []);

            $this->assertTrue($dispatcher->canDispatch($request, $match, $controller));
        }

        private function createRequestMock($path, $method)
        {
            $path = $this->getMock('Psr\Http\Message\UriInterface');
            $path
                ->expects($this->any())
                ->method('getPath')
                ->willReturn($path);

            $request = $this->getMock('Psr\Http\Message\RequestInterface');
            $request
                ->expects($this->any())
                ->method('getMethod')
                ->willReturn($method);
            $request
                ->expects($this->any())
                ->method('getUri')
                ->willReturn($path);

            return $request;
        }
    }
}

namespace Emonkak\Waf\Tests\Action\StandardActionDispatcherTest
{
    class FooController
    {
        public function indexAction()
        {
        }

        public function showAction($id)
        {
        }

        public function editAction($id, $type = 0)
        {
        }

        public function betweenAction($from, $to)
        {
        }

        public function fooBarAction()
        {
        }

        private function bazAction()
        {
        }
    }
}
