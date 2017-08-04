<?php

namespace Core;

use App\Middleware\ControllerMiddleware;
use App\Middleware\FormatNegociatorMiddleware;
use App\Middleware\GoogleAnalyticsMiddleware;
use App\Middleware\PoweredByMiddleware;
use App\Middleware\RouterMiddleware;
use App\Middleware\TraillingSlashMiddleware;
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
        $this->dispatcher->pipe(FormatNegociatorMiddleware::class);
        $this->dispatcher->pipe(TraillingSlashMiddleware::class);
        $this->dispatcher->pipe(PoweredByMiddleware::class);
        $this->dispatcher->pipe(GoogleAnalyticsMiddleware::class);
        $this->dispatcher->pipe(RouterMiddleware::class);
        $this->dispatcher->pipe(ControllerMiddleware::class);
    }

    /**
     * Run your application
     * @return ResponseInterface
     */
    public function run(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
