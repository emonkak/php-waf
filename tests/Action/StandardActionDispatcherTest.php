<?php

namespace Emonkak\Framework\Tests\Action
{
    use Emonkak\Framework\Action\StandardActionDispatcher;
    use Emonkak\Framework\Routing\MatchedRoute;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class StandardActionDispatcherTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideDispatch
         */
        public function testDispatch($uri, $method, $controllerName, $actionName, array $params, $expectedMethod)
        {
            $request = Request::create($uri, $method);
            $response = new Response();

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
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'index', [], 'indexAction'],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'show', ['123'], 'showAction'],
                ['/', 'POST', 'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'edit', ['123'], 'editAction'],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'between', ['123', '456'], 'betweenAction'],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'foo_bar', [], 'fooBarAction'],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpNotFoundException
         * @expectedException Emonkak\Framework\Exception\HttpNotFoundException
         */
        public function testDispatchThrowsHttpNotFoundException($uri, $method, $controllerName, $actionName, array $params)
        {
            $request = Request::create($uri, $method);
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new StandardActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpNotFoundException()
        {
            return [
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'not_found', []],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'FooBar', []],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'FOOBAR', []],
                ['/', 'POST', 'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'Edit', ['123']],
                ['/', 'POST', 'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'EDIT', ['123']],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'Between', ['123', '456']],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'BETWEEN', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpBadRequestException
         * @expectedException Emonkak\Framework\Exception\HttpBadRequestException
         */
        public function testDispatchThrowsHttpBadRequestException($uri, $method, $controllerName, $actionName, array $params)
        {
            $request = Request::create($uri, $method);
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new StandardActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpBadRequestException()
        {
            return [
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'index', ['foo']],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'show', []],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'show', ['foo', 'bar']],
                ['/', 'POST', 'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'edit', ['123', '456', '789']],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'between', ['123', '456', '789']],
                ['/', 'GET',  'Emonkak\Framework\Tests\Action\StandardActionDispatcherTest\FooController', 'foo_bar', ['foo']],
            ];
        }

        public function testCanDispatch()
        {
            $dispatcher = new StandardActionDispatcher();
            $request = new Request();

            $controller = new \StdClass();
            $match = new MatchedRoute('StdClass', 'action', []);

            $this->assertTrue($dispatcher->canDispatch($request, $match, $controller));
        }
    }
}

namespace Emonkak\Framework\Tests\Action\StandardActionDispatcherTest
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
    }
}
