<?php

namespace Emonkak\Framework\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;

/**
 * Router factory.
 */
class RouterBuilder
{
    private $routers = [];

    /**
     * Adda any router.
     *
     * @param RouterInterface $router Any router instance.
     * @return RouterBuilder
     */
    public function add(RouterInterface $router)
    {
        $this->routers[] = $router;
        return $this;
    }

    /**
     * Adds a regexp router which will match the GET method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function get($pattern, $controller, $action)
    {
        return $this->method(Request::METHOD_GET, $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match the POST method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function post($pattern, $controller, $action)
    {
        return $this->method(Request::METHOD_POST, $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match the PUT method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function put($pattern, $controller, $action)
    {
        return $this->method(Request::METHOD_PUT, $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match the DELETE method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function delete($pattern, $controller, $action)
    {
        return $this->method(Request::METHOD_DELETE, $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match any method.
     *
     * @param string $method     The method name for a request.
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function method($method, $pattern, $controller, $action)
    {
        $this->routers[] = new RequestMatcherRouter(
            new RegexpRouter($pattern, $controller, $action),
            new RequestMatcher(null, null, $method)
        );
        return $this;
    }

    /**
     * Adds a regexp router.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function regexp($pattern, $controller, $action)
    {
        $this->routers[] = new RegexpRouter($pattern, $controller, $action);
        return $this;
    }

    /**
     * Adds a resource router
     *
     * @param string $prefix     The prefix of a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @return RouterBuilder
     */
    public function resource($prefix, $controller)
    {
        $this->routers[] = new ResourceRouter($prefix, $controller);
        return $this;
    }

    /**
     * Adds a namespace router.
     *
     * @param string $prefix    The prefix of a request path.
     * @param string $namespace The namespace for controller classes.
     * @return RouterBuilder
     */
    public function mount($prefix, $namespace)
    {
        $this->routers[] = new NamespaceRouter($prefix, $namespace);
        return $this;
    }

    /**
     * Builds a router.
     *
     * @return RouterCollection
     */
    public function build()
    {
        return RouterCollection::from($this->routers);
    }
}
