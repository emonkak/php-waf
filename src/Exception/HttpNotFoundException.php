<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

class HttpNotFoundException extends HttpException
{
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(Response::HTTP_NOT_FOUND, [], $message, $previous);
    }
}
