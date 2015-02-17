<?php

namespace Emonkak\Waf;

use Emonkak\Waf\Action\ActionDispatcherInterface;
use Emonkak\Waf\Action\NullActionDispatcher;
use Emonkak\Waf\Exception\HttpException;
use Emonkak\Waf\Exception\HttpInternalServerErrorException;
use Emonkak\Waf\Exception\HttpNotFoundException;
use Emonkak\Waf\Instantiator\InstantiatorInterface;
use Emonkak\Waf\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * An implementation of kernel for an application.
 */
class Kernel implements KernelInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @var ActionDispatcherInterface
     */
    private $actionDispatcher;

    /**
     * @param RouterInterface           $router
     * @param InstantiatorInterface     $instantiator
     * @param ActionDispatcherInterface $actionDispatcher
     */
    public function __construct(
        RouterInterface $router,
        InstantiatorInterface $instantiator,
        ActionDispatcherInterface $actionDispatcher
    ) {
        $this->router = $router;
        $this->instantiator = $instantiator;
        $this->actionDispatcher = $actionDispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function handleRequest(Request $request)
    {
        $match = $this->router->match($request);
        if ($match === null) {
            throw new HttpNotFoundException('No route matches the request.');
        }

        $controller = $this->instantiator->instantiate($match->controller);

        return $this->actionDispatcher->dispatch($request, $match, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(Request $request, HttpException $exception)
    {
        return new Response('', $exception->getStatusCode(), $exception->getHeaders());
    }
}
