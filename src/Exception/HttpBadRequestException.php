<?php

namespace Emonkak\Waf\Exception;

/**
 * Represents 400 Bad Request.
 */
class HttpBadRequestException extends HttpException
{
    /**
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(400, [], $message, $previous);
    }
}
