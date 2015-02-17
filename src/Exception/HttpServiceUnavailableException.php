<?php

namespace Emonkak\Waf\Exception;

/**
 * Represents 503 Service Unavailable.
 */
class HttpServiceUnavailableException extends HttpException
{
    /**
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(503, [], $message, $previous);
    }
}
