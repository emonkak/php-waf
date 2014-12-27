<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Action\ActionDispatcherInterface;
use Emonkak\Framework\Exception\InternalServerErrorException;
use Emonkak\Framework\Instantiator\InstantiatorInterface;
use Emonkak\Framework\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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
            throw new NotFoundHttpException('No route matches the request.');
        }

        try {
            $controller = $this->instantiator->instantiate($match->controller);
        } catch (\Exception $e) {
            throw new NotFoundHttpException(
                sprintf('Controller "%s" can not be instantiate.', $match->controller),
                $e
            );
        }

        return $this->actionDispatcher->dispatch($request, $match, $controller);
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(Request $request, HttpExceptionInterface $exception)
    {
        throw $exception;
    }
}
