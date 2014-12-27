<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Action\ActionDispatcherInterface;
use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Instantiator\InstantiatorInterface;
use Emonkak\Framework\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

class HttpKernel implements HttpKernelInterface
{
    private $router;
    private $instantiator;
    private $dispatcher;

    public function __construct(
        RouterInterface $router,
        InstantiatorInterface $instantiator,
        ActionDispatcherInterface $dispatcher
    ) {
        $this->router = $router;
        $this->instantiator = $instantiator;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request)
    {
        $match = $this->router->match($request);
        if ($match === null) {
            throw new HttpNotFoundException('No route matches the request.');
        }

        try {
            $controller = $this->instantiator->instantiate($match->controller);
        } catch (\Exception $e) {
            throw new HttpNotFoundException(
                sprintf('Controller "%s" can not be instantiate.', $match->controller),
                $e
            );
        }

        return $this->dispatcher->dispatch($request, $match, $controller);
    }
}
