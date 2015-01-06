<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

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
        parent::__construct(Response::HTTP_FORBIDDEN, [], $message, $previous);
    }
}
