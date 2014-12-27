<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

class HttpBadRequestException extends HttpException
{
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, [], $message, $previous);
    }
}
