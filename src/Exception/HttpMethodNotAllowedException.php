<?php

namespace Emonkak\Waf\Exception;

/**
 * Represents 405 Method Not Allowed.
 */
class HttpMethodNotAllowedException extends HttpException
{
    /**
     * @param string[]        $allow
     * @param string          $message
     * @param \Exception|null $previous
     */
    public function __construct(array $allow, $message = '', \Exception $previous = null)
    {
        $headers = ['Allow' => implode(', ', $allow)];

        parent::__construct(405, $headers, $message, $previous);
    }
}
