<?php

namespace Emonkak\Waf\Controller;

use Emonkak\Di\Annotation\Inject;
use Psr\Http\Message\RequestInterface;

/**
 * Provides access to a request object.
 */
trait RequestAware
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Sets a request object.
     *
     * @Inject
     *
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }
}
