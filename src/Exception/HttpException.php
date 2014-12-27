<?php

namespace Emonkak\Framework\Exception;

class HttpException extends \RuntimeException
{
    private $headers;

    public function __construct($statusCode, array $headers = [], $message = '', \Exception $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);

        $this->headers = $headers;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
