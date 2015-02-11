<?php

namespace Emonkak\Framework\Exception;

/**
 * Represents 403 Forbidden.
 */
class HttpForbiddenException extends HttpException
{
    /**
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(403, [], $message, $previous);
    }
}
