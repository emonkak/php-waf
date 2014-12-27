<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;

class NamespaceRouter implements RouterInterface
{
    private $prefix;
    private $namespace;

    public function __construct($prefix, $namespace)
    {
        $this->prefix = $prefix;
        $this->namespace = $namespace;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Request $request)
    {
        $path = $request->getPathInfo();

        if (strpos($path, $this->prefix) === 0) {
            $rest = explode('/', substr($path, strlen($this->prefix)));
            if (empty($rest[0])) $rest[0] = 'index';
            if (empty($rest[1])) $rest[1] = 'index';

            $controller = $this->getController($rest[0]);
            $action = $rest[1];
            $params = array_slice($rest, 2);

            return new MatchedRoute($controller, $action, $params);
        }

        return null;
    }

    protected function getController($name)
    {
        return $this->namespace . '\\' . StringUtils::camelize($name) . 'Controller';
    }
}
