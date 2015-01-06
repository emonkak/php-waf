<?php

namespace Emonkak\Framework\Tests\Instantiator;

use Emonkak\Framework\Instantiator\SimpleInstantiator;

class SimpleInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $instantiator = new SimpleInstantiator();
        $class = new \ReflectionClass('StdClass');

        $this->assertInstanceOf('StdClass', $instantiator->instantiate($class));
    }
}
