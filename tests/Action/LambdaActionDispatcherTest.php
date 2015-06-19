<?php

namespace Emonkak\Waf\Tests\Action
{
    use Emonkak\Waf\Action\LambdaActionDispatcher;
    use Emonkak\Waf\Routing\MatchedRoute;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class LambdaActionDispatcherTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider provideDispatch
         */
        public function testDispatch($controllerName, $actionName, array $params, $expectedParams)
        {
            $request = new Request();
            $response = new Response();

            $controllerMock = $this->getMock($controllerName);
            $invocationMock = $controllerMock
                ->expects($actionSpy = $this->once())
                ->method('__invoke')
                ->willReturn($response);

            call_user_func_array(
                [$invocationMock, 'with'],
                array_map([$this, 'identicalTo'], $expectedParams)
            );

            $match = new MatchedRoute(get_class($controllerMock), $actionName, $params);
            $dispatcher = new LambdaActionDispatcher();

            $this->assertSame($response, $dispatcher->dispatch($request, $match, $controllerMock));
            $this->assertCount(1, $actionSpy->getInvocations());
        }

        public function provideDispatch()
        {
            return [
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\FooController', 'index', [], []],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BarController', 'foo', [], ['foo']],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BarController', 'index', ['foo'], ['foo']],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BazController', 'index', ['foo', 'bar'], ['foo', 'bar']],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BazController', 'foo', ['bar'], ['foo', 'bar']],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpNotFoundException
         * @expectedException Emonkak\Waf\Exception\HttpNotFoundException
         */
        public function testDispatchThrowsHttpNotFoundException($controllerName, $actionName, array $params)
        {
            $request = new Request();
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new LambdaActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpNotFoundException()
        {
            return [
                ['stdClass', 'foo', []],
            ];
        }

        /**
         * @dataProvider provideDispatchThrowsHttpBadRequestException
         * @expectedException Emonkak\Waf\Exception\HttpBadRequestException
         */
        public function testDispatchThrowsHttpBadRequestException($controllerName, $actionName, array $params)
        {
            $request = new Request();
            $match = new MatchedRoute($controllerName, $actionName, $params);
            $controller = new $controllerName();

            $dispatcher = new LambdaActionDispatcher();
            $dispatcher->dispatch($request, $match, $controller);
        }

        public function provideDispatchThrowsHttpBadRequestException()
        {
            return [
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\FooController', 'index', ['foo']],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BarController', 'index', []],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BarController', 'foo', ['bar']],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BazController', 'index', []],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BazController', 'foo', []],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BazController', 'foo', ['bar', 'baz']],
            ];
        }

        /**
         * @dataProvider provideCanDispatch
         */
        public function testCanDispatch($controllerName, $actionName, $params, $expectedResult)
        {
            $dispatcher = new LambdaActionDispatcher();
            $request = new Request();
            $match = new MatchedRoute($controllerName, 'action', []);
            $controller = new $controllerName();

            $this->assertSame($expectedResult, $dispatcher->canDispatch($request, $match, $controller));
        }

        public function provideCanDispatch()
        {
            return [
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\FooController', 'index', [], true],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BarController', 'foo', [], true],
                ['Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest\BazController', 'foo', ['bar'], true],
                ['stdClass', 'foo', [], false],
            ];
        }
    }
}

namespace Emonkak\Waf\Tests\Action\LambdaActionDispatcherTest
{
    class FooController
    {
        public function __invoke()
        {
        }
    }

    class BarController
    {
        public function __invoke($foo)
        {
        }
    }

    class BazController
    {
        public function __invoke($foo, $bar)
        {
        }
    }
}
