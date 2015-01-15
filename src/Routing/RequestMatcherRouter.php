<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

class RequestMatcherRouter implements RouterInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestMatcherInterface
     */
    private $matcher;

    /**
     * @param RouterInterface         $router
     * @param RequestMatcherInterface $matcher
     */
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

    /**
     * {@inheritDoc}
     */
    public function getPattern()
    {
        return $this->router->getPattern();
    }
}
