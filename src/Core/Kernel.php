<?php

namespace Core;

use DI\ContainerBuilder;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
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
        $this->container = $container;
        if (! $this->container) {
            $containerBuilder = new ContainerBuilder();
            //$containerBuilder->useAutowiring(true);
            $containerBuilder->addDefinitions(__DIR__.'/config.php');

            $this->container = $containerBuilder->build();
        }

        $this->request = $this->container->get(RequestInterface::class);
        $this->response = ($response)?:new Response();


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
    public function run()
    {
        return $this->response;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
