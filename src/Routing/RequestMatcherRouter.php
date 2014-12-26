<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class RequestMatcherRouter implements RouterInterface
{
    private $matcher;
    private $router;

    public function __construct(RequestMatcherInterface $matcher, RouterInterface $router)
    {
        $this->matcher = $matcher;
        $this->router = $router;
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
