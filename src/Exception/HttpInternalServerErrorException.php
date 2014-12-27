<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

class HttpInternalServerErrorException extends HttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, [], $message, $previous);
    }
}
