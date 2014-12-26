<?php

namespace Emonkak\Framework;

use Emonkak\Framework\Action\ActionDispatcherInterface;
use Emonkak\Framework\Exception\HttpNotFoundException;
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

    public function handle(Request $request)
    {
        $action = $this->router->match($request);
        if ($action === null) {
            throw new HttpNotFoundException('No route matches the request.');
        }

        $controller = $action->instantiateBy($this->instantiator);

        return $this->dispatcher->dispatch($request, $action, $controller);
    }
}
