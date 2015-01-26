<?php

namespace Emonkak\Framework\Controller;

use Emonkak\Di\Annotations\Inject;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides access to a request object.
 */
trait RequestAware
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Sets the template engine.
     *
     * @Inject
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
