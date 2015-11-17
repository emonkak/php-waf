<?php

namespace Emonkak\Waf\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Provides controller helper methods.
 */
trait ControllerHelper
{
    /**
     * Creates a HTML response.
     *
     * @param string  $content The content of HTML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return Response
     */
    protected function html($content, $status = 200, array $headers = [])
    {
        $response = new Response($content, $status, $headers);
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    /**
     * Creates a plain text response.
     *
     * @param string  $content The content of HTML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return Response
     */
    protected function text($content, $status = 200, array $headers = [])
    {
        $response = new Response($content, $status, $headers);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }

    /**
     * Creates a XML response.
     *
     * @param string  $content The content of XML.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return Response
     */
    protected function xml($content, $status = 200, array $headers = [])
    {
        $response = new Response($content, $status, $headers);
        $response->headers->set('Content-Type', 'application/xml');
        return $response;
    }

    /**
     * Creates a JSON response.
     *
     * @param mixed   $data    The data of JSON.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return JsonResponse
     */
    protected function json($data, $status = 200, array $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Creates a redirect response.
     *
     * @param string  $url     The location url to redirect.
     * @param integer $status  The status code of this response.
     * @param array   $headers HTTP headers of this response.
     * @return RedirectResponse
     */
    protected function redirect($url, $status = 302, array $headers = [])
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * Creates a streamed response.
     *
     * @param callable|null $callback The callback associated with this response.
     * @param integer       $status   The status code of this response.
     * @param array         $headers  HTTP headers of this response.
     * @return StreamedResponse
     */
    protected function stream($callback = null, $status = 200, array $headers = [])
    {
        return new StreamedResponse($callback, $status, $headers);
    }
}
