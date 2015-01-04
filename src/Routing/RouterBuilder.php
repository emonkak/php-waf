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

    public function add(RouterInterface $router)
    {
        $this->routers[] = $router;
        return $this;
    }

    public function mount($prefix, $namespace)
    {
        $this->routers[] = new NamespaceRouter($prefix, $namespace);
        return $this;
    }

    public function resource($prefix, $controller)
    {
        $this->routers[] = new ResourceRouter($prefix, $controller);
        return $this;
    }

    public function get($regexp, $controller, $action)
    {
        return $this->method('GET', $regexp, $controller, $action);
    }

    public function post($regexp, $controller, $action)
    {
        return $this->method('POST', $regexp, $controller, $action);
    }

    public function put($regexp, $controller, $action)
    {
        return $this->method('PUT', $regexp, $controller, $action);
    }

    public function delete($regexp, $controller, $action)
    {
        return $this->method('DELETE', $regexp, $controller, $action);
    }

    public function method($method, $regexp, $controller, $action)
    {
        $this->routers[] = new RequestMatcherRouter(
            new RegexpRouter($regexp, $controller, $action),
            new RequestMatcher(null, null, $method)
        );
        return $this;
    }

    public function regexp($regexp, $controller, $action)
    {
        $this->routers[] = new RegexpRouter($regexp, $controller, $action);
        return $this;
    }

    public function build()
    {
        return RouterCollection::from($this->routers);
    }
}
