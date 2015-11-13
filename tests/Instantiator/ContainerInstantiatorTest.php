<?php

namespace Emonkak\Waf\Tests\Instantiator;

use Emonkak\Waf\Instantiator\ContainerInstantiator;

class ContainerInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $className = 'stdClass';
        $instance = (object) ['foo' => 'bar'];

        $container = $this->getMock('Interop\Container\ContainerInterface');
        $container
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo($className))
            ->willReturn($instance);

        $instantiator = new ContainerInstantiator($container);

        $this->assertEquals($instance, $instantiator->instantiate($className));
    }
}
