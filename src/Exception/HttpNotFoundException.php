<?php

namespace Emonkak\Framework\Exception;

class HttpNotFoundException extends HttpException
{
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(404, [], $message, $previous);
    }
}
