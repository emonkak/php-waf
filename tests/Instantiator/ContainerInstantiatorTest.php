<?php

namespace Emonkak\Framework\Tests\Instantiator;

use Doctrine\Common\Cache\ArrayCache;
use Emonkak\Di\Container;
use Emonkak\Di\Value\ImmediateValue;
use Emonkak\Framework\Instantiator\ContainerInstantiator;

class ContainerInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $className = 'stdClass';
        $instance = new \stdClass();
        $value = new ImmediateValue($instance);

        $container = $this->getMockBuilder('Emonkak\Di\Container')
            ->disableOriginalConstructor()
            ->getMock();
        $container
            ->expects($this->once())
            ->method('get')
            ->with($this->identicalTo($className))
            ->willReturn($value);

        $configurator = $this->getMock('stdClass', ['__invoke']);
        $configurator
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo($container));

        $cache = new ArrayCache();
        $instantiator = new ContainerInstantiator($container, $cache);
        $instantiator->addConfigurator($configurator);

        $this->assertSame($instance, $instantiator->instantiate($className));
        $this->assertSame($instance, $instantiator->instantiate($className));
    }
}