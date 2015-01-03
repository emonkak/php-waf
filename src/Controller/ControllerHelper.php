<?php

namespace Emonkak\Framework\Controller;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

trait ControllerHelper
{
    public function html($content, $status = Response::HTTP_OK, array $headers = [])
    {
        $response = $this->createResponse($content, $status, $headers);
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    public function text($content, $status = Response::HTTP_OK, array $headers = [])
    {
        $response = $this->createResponse($content, $status, $headers);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }

    public function xml($content, $status = Response::HTTP_OK, array $headers = [])
    {
        $response = $this->createResponse($content, $status, $headers);
        $response->headers->set('Content-Type', 'application/xml');
        return $response;
    }

    public function json($data, $status = Response::HTTP_OK, array $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }

    public function redirect($url, $status = Response::HTTP_FOUND, array $headers = [])
    {
        return new RedirectResponse($data, $status, $headers);
    }

    public function createResponse($message = '', \Exception $previous = null)
    {
        return new Response($content, $status, $headers);
    }

    public function createNotFoundException($message = '', \Exception $previous = null)
    {
        return new HttpNotFoundException($message, $previous);
    }
}
