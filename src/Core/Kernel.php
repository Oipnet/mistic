<?php

namespace Core;

use App\Middleware\RouterMiddleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Kernel
 */
class Kernel
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var \Core\Dispatcher
     */
    private $dispatcher;

    public function __construct()
    {
        $this->request = ServerRequest::fromGlobals();
        $this->response = new Response();

        $this->dispatcher = new Dispatcher();
        $this->dispatcher->pipe(\App\Middleware\FormatNegociatorMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\TraillingSlashMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\PoweredByMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\GoogleAnalyticsMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\RouterMiddleware::class);
    }

    /**
     * Run your application
     */
    public function run() {
        \Http\Response\send($this->dispatcher->process($this->request));
    }
}