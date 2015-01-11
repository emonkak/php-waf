<?php

namespace Emonkak\Framework\Tests\Instantiator;

use Emonkak\Framework\Instantiator\SimpleInstantiator;

class SimpleInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $instantiator = new SimpleInstantiator();
        $this->assertInstanceOf('stdClass', $instantiator->instantiate('stdClass'));
    }
}
