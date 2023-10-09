<?php

namespace CrossSymfony\CorsGeneratorBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseListener
{
    private $logger;

    private $max_age;
    private $allow_credentials;
    private $allow_origin;
    private $allow_headers;
    private $allow_methods;

    public function __construct(
        int $max_age,
        bool $allow_credentials,
        string $allow_origin,
        string $allow_headers,
        string $allow_methods,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->max_age = $max_age;
        $this->allow_credentials = $allow_credentials;
        $this->allow_origin = $allow_origin;
        $this->allow_headers = $allow_headers;
        $this->allow_methods = $allow_methods;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $this->logger->info('Response listener is working!');

        // Get the response object
        $response = $event->getResponse();

        // Allow requests from any origin
        if ($this->allow_origin) {
            $response->headers->set('Access-Control-Allow-Origin', $this->allow_origin);
        }

        // Allow the following HTTP methods
        if ($this->allow_methods) {
            $response->headers->set('Access-Control-Allow-Methods', $this->allow_methods);
        }

        // Allow the following headers
        if ($this->allow_headers) {
            $response->headers->set('Access-Control-Allow-Headers', $this->allow_headers);
        }

        // Allow cookies to be sent from the client
        if ($this->allow_credentials) {
            $response->headers->set('Access-Control-Allow-Credentials', $this->allow_credentials);
        }

        // Set the maximum age for preflight requests (in seconds)
        if ($this->max_age) {
            $response->headers->set('Access-Control-Max-Age', $this->max_age);
        }
    }
}
