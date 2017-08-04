<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 29/07/17
 * Time: 14:14
 */

namespace Core;


use App\Middleware\Exceptions\MiddlewareNotFound;
use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher implements DelegateInterface
{
    private $middlewares = [];
    private $index = 0;
    private $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * @param MiddlewareInterface|string $middleware
     */
    public function pipe($middleware)
    {
        if (is_string($middleware )) {
            $middleware = new $middleware();
        }

        if (! $middleware instanceof MiddlewareInterface) {
            throw new MiddlewareNotFound(get_class($middleware). ' is not a '.MiddlewareInterface::class);
        }

        $this->middlewares[] = $middleware;
        $this->response = new Response();
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