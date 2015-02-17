<?php

namespace Emonkak\Waf\Exception;

/**
 * Represents 404 Not Found.
 */
class HttpNotFoundException extends HttpException
{
    /**
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(404, [], $message, $previous);
    }
}
