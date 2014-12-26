<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\RequestMatcher;

class RouterBuilder
{
    /**
     * @var RouterInterface[]
     */
    private $routers = [];

    public function __construct()
    {
        $this->collection = new RouterCollection();
    }

    public function mount($prefix, $namespace)
    {
        $this->routers[] = new NamespaceRouter($prefix, $namespace);
        return $this;
    }

    public function get($regexp, $controller, $action = null)
    {
        return $this->method('GET', $regexp, $controller, $action);
    }

    public function post($regexp, $controller, $action = null)
    {
        return $this->method('POST', $regexp, $controller, $action);
    }

    public function put($regexp, $controller, $action = null)
    {
        return $this->method('PUT', $regexp, $controller, $action);
    }

    public function delete($regexp, $controller, $action = null)
    {
        return $this->method('DELETE', $regexp, $controller, $action);
    }

    public function method($method, $regexp, $controller, $action = null)
    {
        $matcher = new RequestMatcher(null, null, $method);

        if ($action !== null) {
            $this->routers[] = new RequestMatcherRouter(
                $matcher,
                new StaticRegexpRouter($regexp, $controller, $action)
            );
        } else {
            $this->routers[] = new RequestMatcherRouter(
                $matcher,
                new DynamicRegexpRouter($regexp, $controller)
            );
        }
        return $this;
    }

    public function regexp($regexp, $controller, $action = null)
    {
        if ($action !== null) {
            $this->routers[] = new StaticRegexpRouter($regexp, $controller, $action);
        } else {
            $this->routers[] = new DynamicRegexpRouter($regexp, $controller);
        }
        return $this;
    }

    public function build()
    {
        return new RouterCollection($this->routers);
    }
}
