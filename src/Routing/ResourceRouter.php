<?php

namespace Emonkak\Waf\Routing;

use Emonkak\Waf\Exception\HttpNotFoundException;
use Emonkak\Waf\Exception\HttpRedirectException;
use Emonkak\Waf\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a Restful resource routing.
 *
 * This follows a pattern such as '/{controller}/{resource}/{action}/{params}...'.
 */
class ResourceRouter implements RouterInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $controller;

    /**
     * @param string $prefix     The prefix of a request path.
     * @param string $controller The fully qualified class name of the controller.
     */
    public function __construct($prefix, $controller)
    {
        $this->prefix = $prefix;
        $this->controller = $controller;
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
            $fragments = array_filter(explode('/', substr($path, strlen($this->prefix))), 'strlen');

            if (empty($fragments[0])) {
                $action = 'index';
                $params = [];
            } elseif (!isset($fragments[1])) {
                $action = 'show';
                $params = [$fragments[0]];
            } else {
                $action = empty($fragments[1]) ? 'index' : $fragments[1];
                $params = array_merge([$fragments[0]], array_slice($fragments, 2));
            }

            return new MatchedRoute($this->controller, $action, $params);
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
}
