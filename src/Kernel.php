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

class Kernel implements KernelInterface
{
    private $router;
    private $instantiator;
    private $actionDispatcher;

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
        throw $exception;
    }
}
