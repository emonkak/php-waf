<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

class HttpServiceUnavailableException extends HttpException
{
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(Response::HTTP_SERVICE_UNAVAILABLE, [], $message, $previous);
    }
}
