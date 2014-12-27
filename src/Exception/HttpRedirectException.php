<?php

namespace Emonkak\Framework\Exception;

use Symfony\Component\HttpFoundation\Response;

class HttpRedirectException extends HttpException
{
    public function __construct($location, $statusCode = Response::HTTP_FOUND, $message = '', \Exception $previous = null)
    {
        if ($statusCode < 300 || $statusCode >= 400) {
            throw new \InvalidArgumentException('Invalid HTTP redirect status code: ' . $statusCode);
        }

        parent::__construct($statusCode, [
            'Location' => $location
        ]);
    }
}
