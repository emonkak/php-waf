<?php

namespace Emonkak\Framework\Routing;

use Emonkak\Framework\Action\LambdaAction;
use Symfony\Component\HttpFoundation\Request;

class DynamicRegexpRouter implements RouterInterface
{
    private $regexp;
    private $lambda;

    public function __construct($regexp, \Closure $lambda)
    {
        $this->regexp = $regexp;
        $this->lambda = $lambda;
    }

    public function match(Request $request)
    {
        $path = $request->getPathInfo();
        $length = preg_match($this->regexp, $path, $matches);

        if ($length > 0) {
            $args = array_slice($matches, 1);
            return new LambdaAction($this->lambda, $args);
        }

        return null;
    }
}
