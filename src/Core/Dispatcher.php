<?php

namespace Core;

use App\Middleware\Exceptions\MiddlewareNotFound;
use DI\Definition\Exception\DefinitionException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher implements DelegateInterface
{
    private $middlewares = [];
    private $index = 0;
    private $response;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ResponseInterface $response, ContainerInterface $container)
    {
        $this->response = $response;
        $this->container = $container;
    }

    /**
     * @param MiddlewareInterface|string $middleware
     */
    public function pipe($middleware)
    {
        if (is_string($middleware)) {
            try {
                $middleware = $this->container->get($middleware);
            } catch (DefinitionException $e) {
                throw new MiddlewareNotFound($middleware. ' can\'t be load');
            }
        }

        if (! $middleware instanceof MiddlewareInterface) {
            throw new MiddlewareNotFound(get_class($middleware). ' is not a '.MiddlewareInterface::class);
        }

        $this->middlewares[] = $middleware;
    }


    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        $this->index++;

        if (! $middleware) {
            return $this->response;
        }

        return $middleware->process($request, $this);
    }

    private function getMiddleware()
    {
        if (isset($this->middlewares[$this->index])) {
            return $this->middlewares[$this->index];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
