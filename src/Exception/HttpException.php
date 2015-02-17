<?php

namespace Emonkak\Waf\Exception;

/**
 * Base class for HTTP exceptions.
 */
class HttpException extends \RuntimeException
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @param integer    $statusCode The status code for HTTP request
     * @param array      $headers    The headers for HTTP request.
     * @param string     $message
     * @param \Exception $previous
     */
    public function __construct($statusCode, array $headers = [], $message = '', \Exception $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);

        $this->headers = $headers;
    }

    /**
     * Gets the status code for HTTP request.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * Gets the headers for HTTP request.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
