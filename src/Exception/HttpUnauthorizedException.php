<?php

namespace Emonkak\Framework\Exception;

/**
 * Represents 401 unauthorized
 */
class HttpUnauthorizedException extends HttpException
{
    /**
     * @param string          $challenge
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($challenge, $message = '', \Exception $previous = null)
    {
        parent::__construct(401, ['WWW-Authenticate' => $challenge], $message, $previous);
    }
}
