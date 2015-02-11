<?php

namespace Emonkak\Framework\Tests\Controller;

use Emonkak\Framework\Exception\HttpRedirectException;

class HttpRedirectExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStatusCode()
    {
        $exception = new HttpRedirectException('/path/to/redirect/');
        $this->assertSame(302, $exception->getStatusCode());
    }

    public function testGetHeaders()
    {
        $exception = new HttpRedirectException('/path/to/redirect/');
        $this->assertSame(['Location' => '/path/to/redirect/'], $exception->getHeaders());
    }

    public function testGetMessage()
    {
        $exception = new HttpRedirectException('/path/to/redirect/', 302, 'redirect');
        $this->assertSame('redirect', $exception->getMessage());
    }

    public function testGetPrevious()
    {
        $previous = new \Exception();
        $exception = new HttpRedirectException('/path/to/redirect/', 302, 'redirect', $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidStatusCodeGiven
     */
    public function testInvalidStatusCodeGiven($statusCode)
    {
        new HttpRedirectException('/path/to/redirect/', $statusCode);
    }

    public function provideInvalidStatusCodeGiven()
    {
        return [
            [200],
            [400]
        ];
    }
}
