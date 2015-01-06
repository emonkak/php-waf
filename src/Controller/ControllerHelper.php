<?php

namespace Emonkak\Framework\Controller;

use Emonkak\Framework\Exception\HttpBadRequestException;
use Emonkak\Framework\Exception\HttpForbiddenException;
use Emonkak\Framework\Exception\HttpNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides controller helper methods.
 */
trait ControllerHelper
{
    /**
     * Creates a HTML response.
     *
     * @param string  $context The content of HTML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return Response
     */
    public function html($content, $status = Response::HTTP_OK, array $headers = [])
    {
        $response = new Response($content, $status, $headers);
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    /**
     * Creates a plain text response.
     *
     * @param string  $context The content of HTML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return Response
     */
    public function text($content, $status = Response::HTTP_OK, array $headers = [])
    {
        $response = new Response($content, $status, $headers);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }

    /**
     * Creates a XML response.
     *
     * @param string  $context The content of HTML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return Response
     */
    public function xml($content, $status = Response::HTTP_OK, array $headers = [])
    {
        $response = new Response($content, $status, $headers);
        $response->headers->set('Content-Type', 'application/xml');
        return $response;
    }

    /**
     * Creates a JSON response.
     *
     * @param string  $context The content of HTML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return JsonResponse
     */
    public function json($data, $status = Response::HTTP_OK, array $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Creates a redirect response.
     *
     * @param string  $context The content of HTML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return RedirectResponse
     */
    public function redirect($url, $status = Response::HTTP_FOUND, array $headers = [])
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * Creates HTTP bad request exception.
     *
     * @param string     $message
     * @param \Exception $previous
     * @return HttpBadRequestException
     */
    public function createBadRequestException($message = '', \Exception $previous = null)
    {
        return new HttpBadRequestException($message, $previous);
    }

    /**
     * Creates HTTP forbidden exception.
     *
     * @param string     $message
     * @param \Exception $previous
     * @return HttpForbiddenException
     */
    public function createForbiddenException($message = '', \Exception $previous = null)
    {
        return new HttpForbiddenException($message, $previous);
    }

    /**
     * Creates HTTP not found exception.
     *
     * @param string     $message
     * @param \Exception $previous
     * @return HttpNotFoundException
     */
    public function createNotFoundException($message = '', \Exception $previous = null)
    {
        return new HttpNotFoundException($message, $previous);
    }
}
