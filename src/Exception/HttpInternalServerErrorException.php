<?php

namespace Emonkak\Waf\Exception;

/**
 * Represents 500 Internal Server Error.
 */
class HttpInternalServerErrorException extends HttpException
{
    /**
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(500, [], $message, $previous);
    }
}
