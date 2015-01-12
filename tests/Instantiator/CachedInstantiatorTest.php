<?php

namespace Emonkak\Framework\Tests\Instantiator;

use Doctrine\Common\Cache\ArrayCache;
use Emonkak\Framework\Instantiator\CachedInstantiator;

class CachedInstantiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $className = 'stdClass';
        $instance = (object) ['foo' => 'bar'];

        $innerInstantiator = $this->getMock('Emonkak\Framework\Instantiator\InstantiatorInterface');
        $innerInstantiator
            ->expects($this->once())
            ->method('instantiate')
            ->with($this->identicalTo($className))
            ->willReturn($instance);

        $cache = new ArrayCache();
        $instantiator = new CachedInstantiator($innerInstantiator, $cache);

        $this->assertEquals($instance, $instantiator->instantiate($className));
        $this->assertEquals($instance, $instantiator->instantiate($className));
    }
}
