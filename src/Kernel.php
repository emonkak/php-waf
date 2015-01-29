<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Action\ActionDispatcherInterface;
use Emonkak\Framework\Action\NullActionDispatcher;
use Emonkak\Framework\Exception\HttpException;
use Emonkak\Framework\Exception\HttpInternalServerErrorException;
use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Instantiator\InstantiatorInterface;
use Emonkak\Framework\Routing\RouterInterface;
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
