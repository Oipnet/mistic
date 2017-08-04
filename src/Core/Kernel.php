<?php

namespace Core;

use App\Middleware\RouterMiddleware;
use DI\Container;
use DI\ContainerBuilder;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
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
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * Kernel constructor.
     * @param null|ResponseInterface $response
     * @param ContainerInterface|null $container
     */
    public function __construct(?ResponseInterface $response = null, ?ContainerInterface $container = null)
    {
        $this->request = ServerRequest::fromGlobals();
        $this->response = ($response)?:new Response();
        $this->container = $container;

        if (! $this->container) {
            $containerBuilder = new ContainerBuilder();
            //$containerBuilder->useAutowiring(true);
            $containerBuilder->addDefinitions(__DIR__.'/config.php');

            $this->container = $containerBuilder->build();
        }

        $this->dispatcher = new Dispatcher($this->getContainer()->get(ResponseInterface::class), $this->container);
        $this->dispatcher->pipe(\App\Middleware\FormatNegociatorMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\TraillingSlashMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\PoweredByMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\GoogleAnalyticsMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\RouterMiddleware::class);
        $this->dispatcher->pipe(\App\Middleware\ControllerMiddleware::class);
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

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}