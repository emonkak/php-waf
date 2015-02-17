<?php

namespace Emonkak\Waf\Tests\Action
{
    use Emonkak\Waf\Action\RestActionDispatcher;
    use Emonkak\Waf\Routing\MatchedRoute;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class RestActionDispatcherTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideDispatch
         */
        public function testDispatch($path, $method, $controllerName, $actionName, array $params, $expectedMethod)
        {
            $request = Request::create($path, $method);
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
            $dispatcher = new RestActionDispatcher();

            $this->assertSame($response, $dispatcher->dispatch($request, $match, $controllerMock));
            $this->assertCount(1, $actionSpy->getInvocations());
        }

        public function provideDispatch()
        {
            return [
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'index', [], 'getIndex'],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'show', ['123'], 'getShow'],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'edit', ['123'], 'postEdit'],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'between', ['123', '456'], 'getBetween'],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'foo_bar', [], 'getFooBar'],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpNotFoundException
         * @expectedException Emonkak\Waf\Exception\HttpNotFoundException
         */
        public function testDispatchThrowsHttpNotFoundException($path, $method, $controllerName, $actionName, array $params)
        {
            $request = Request::create($path, $method);
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new RestActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpNotFoundException()
        {
            return [
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'not_found', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'FooBar', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'FOOBAR', []],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'Edit', ['123']],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'EDIT', ['123']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'Between', ['123', '456']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'BETWEEN', ['123', '456']],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpBadRequestException
         * @expectedException Emonkak\Waf\Exception\HttpBadRequestException
         */
        public function testDispatchThrowsHttpBadRequestException($path, $method, $controllerName, $actionName, array $params)
        {
            $request = Request::create($path, $method);
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new RestActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpBadRequestException()
        {
            return [
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'index', ['foo']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'show', []],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'show', ['foo', 'bar']],
                ['/', 'POST', 'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'edit', ['123', '456', '789']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'between', ['123', '456', '789']],
                ['/', 'GET',  'Emonkak\Waf\Tests\Action\RestActionDispatcherTest\FooController', 'foo_bar', ['foo']],
            ];
        }

        public function testCanDispatch()
        {
            $dispatcher = new RestActionDispatcher();
            $request = new Request();
            $match = new MatchedRoute('StdClass', 'action', []);
            $controller = new \StdClass();

            $this->assertTrue($dispatcher->canDispatch($request, $match, $controller));
        }
    }
}

namespace Emonkak\Waf\Tests\Action\RestActionDispatcherTest
{
    class FooController
    {
        public function getIndex()
        {
        }

        public function getShow($id)
        {
        }

        public function postEdit($id, $type = 0)
        {
        }

        public function getBetween($from, $to)
        {
        }

        public function getFooBar()
        {
        }
    }
}
