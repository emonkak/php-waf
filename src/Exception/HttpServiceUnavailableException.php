<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

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
        parent::__construct(Response::HTTP_SERVICE_UNAVAILABLE, [], $message, $previous);
    }
}
