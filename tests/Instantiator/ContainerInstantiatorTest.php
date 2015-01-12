<?php

namespace Emonkak\Framework\Tests\Instantiator;

use Doctrine\Common\Cache\ArrayCache;
use Emonkak\Di\Container;
use Emonkak\Di\InjectionPolicy\DefaultInjectionPolicy;
use Emonkak\Di\Value\ImmediateValue;
use Emonkak\Framework\Instantiator\ContainerInstantiator;

class ContainerInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $className = 'stdClass';
        $instance = (object) ['foo' => 'bar'];

        $container = new Container(new DefaultInjectionPolicy(), new ArrayCache());
        $container->set($className, $instance);

        $configurator = $this->getMock('stdClass', ['__invoke']);
        $configurator
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->identicalTo($container));

        $instantiator = new ContainerInstantiator($container);
        $instantiator->addConfigurator($configurator);

        $this->assertEquals($instance, $instantiator->instantiate($className));
    }
}
