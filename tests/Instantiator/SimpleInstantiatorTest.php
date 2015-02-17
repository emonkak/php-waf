<?php

namespace Emonkak\Waf\Tests\Instantiator;

use Emonkak\Waf\Instantiator\SimpleInstantiator;

class SimpleInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $instantiator = new SimpleInstantiator();
        $this->assertInstanceOf('stdClass', $instantiator->instantiate('stdClass'));
    }
}
