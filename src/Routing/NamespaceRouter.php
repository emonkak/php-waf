<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Exception\HttpRedirectException;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a routing from a namespace for controller classes.
 *
 * This follows a pattern such as "/{controller}/{action}/{params}...".
 */
class NamespaceRouter implements RouterInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @param string $prefix    The prefix of a request path.
     * @param string $namespace The namespace for controller classes.
     */
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

        if (StringUtils::forgetsTrailingSlash($path, $this->prefix)) {
            throw new HttpRedirectException($request->getBaseUrl() . $path . '/', 301);
        }

        if (StringUtils::startsWith($path, $this->prefix)) {
            $fragments = explode('/', substr($path, strlen($this->prefix)));

            if (count($fragments) <= 1 && substr($path, -1) !== '/') {
                throw new HttpRedirectException($request->getBaseUrl() . $path . '/', 301);
            }

            $controller = empty($fragments[0]) ? 'index' : $fragments[0];
            $controller = $this->getController($controller);
            $action = empty($fragments[1]) ? 'index' : $fragments[1];
            $params = array_slice($fragments, 2);

            return new MatchedRoute($controller, $action, $params);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getPattern()
    {
        return preg_quote($this->prefix, self::PATTERN_DELIMITER);
    }

    /**
     * Returns the fully qualified controller class.
     *
     * @param string $name The fragment of controller name.
     * @return string
     * @throws HttpException
     */
    protected function getController($name)
    {
        if ($name !== strtolower($name)) {
            throw new HttpNotFoundException('The controller name must contain only lowercase letters.');
        }

        $controller = sprintf(
            '%s\\%sController',
            $this->namespace,
            StringUtils::toUpperCamelcase($name)
        );

        if (!class_exists($controller, true)) {
            throw new HttpNotFoundException(
                sprintf('Controller "%s" can not be found.', $controller)
            );
        }

        return $controller;
    }
}
