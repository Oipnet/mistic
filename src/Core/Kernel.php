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

    /**
     * Kernel constructor.
     * @param null|ResponseInterface $response
     */
    public function __construct(?ResponseInterface $response = null)
    {
        $this->request = ServerRequest::fromGlobals();
        $this->response = ($response)?:new Response();

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
        $this->sendResponse();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function sendResponse(): void
    {
        \Http\Response\send($this->dispatcher->process($this->request));
    }
}