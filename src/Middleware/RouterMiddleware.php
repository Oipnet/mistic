<?php

namespace App\Middleware;

use App\Controller\BlogController;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouterMiddleware
 * @package App\Middleware
 */
class RouterMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * RouterMiddleware constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        //$response = $delegate->process($request);

        $url = $request->getUri()->getPath();

        if ($url === '/blog') {
            $request = $request->withAttribute('controller', BlogController::class);
            $request = $request->withAttribute('action', 'index');
            return $delegate->process($request);
        }

        $this->response->getBody()->write('Ooops 404');

        return $this->response->withStatus(404);
    }
}
