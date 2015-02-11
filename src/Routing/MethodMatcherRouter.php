<?php

namespace Emonkak\Waf\Routing;

use Psr\Http\Message\RequestInterface;

class MethodMatcherRouter implements RouterInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $method;

    /**
     * @param RouterInterface $router
     * @param string          $method
     */
    public function __construct(RouterInterface $router, $method)
    {
        $this->router = $router;
        $this->method = $method;
    }

    /**
     * {@inheritDoc}
     */
    public function match(RequestInterface $request)
    {
        if ($request->getMethod() === $this->method) {
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
