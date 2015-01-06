<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Represents a redirect exception.
 */
class HttpRedirectException extends HttpException
{
    /**
     * @param string          $location
     * @param integer         $statusCode
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct($location, $statusCode = Response::HTTP_FOUND, $message = '', \Exception $previous = null)
    {
        if ($statusCode < 300 || $statusCode >= 400) {
            throw new \InvalidArgumentException('Invalid HTTP redirect status code: ' . $statusCode);
        }

        parent::__construct($statusCode, ['Location' => $location], $message, $previous);
    }
}
