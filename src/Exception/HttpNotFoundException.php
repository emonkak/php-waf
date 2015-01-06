<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

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
        parent::__construct(Response::HTTP_NOT_FOUND, [], $message, $previous);
    }
}
