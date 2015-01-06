<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

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
        parent::__construct(Response::HTTP_BAD_REQUEST, [], $message, $previous);
    }
}
