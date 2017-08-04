<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 29/07/17
 * Time: 13:43
 */

namespace App\Middleware;


use App\Controller\BlogController;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouterMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

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
        } /*elseif ($url === '/contact') {
            $response->getBody()->write('Me contacter');
        } */else {
            $this->response->getBody()->write('Ooops 404');

            return $this->response->withStatus(404);
        }

        return $delegate->process($request);
    }
}