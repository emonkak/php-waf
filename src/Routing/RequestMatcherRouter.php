<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class RequestMatcherRouter implements RouterInterface
{
    private $router;
    private $matcher;

    public function __construct(RouterInterface $router, RequestMatcherInterface $matcher)
    {
        $this->router = $router;
        $this->matcher = $matcher;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        if ($this->matcher->matches($request)) {
            return $this->router->match($request);
        }

        return null;
    }
}
