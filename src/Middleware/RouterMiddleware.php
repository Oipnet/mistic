<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 29/07/17
 * Time: 13:43
 */

namespace App\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouterMiddleware implements MiddlewareInterface
{

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
        $response = $delegate->process($request);

        $url = $request->getUri()->getPath();

        if ($url === '/blog') {
            $response->getBody()->write('<body>Je suis sur le blog</body>');
        } elseif ($url === '/contact') {
            $response->getBody()->write('Me contacter');
        } else {
            $response->getBody()->write('Ooops 404');
            $response = $response->withStatus(404);
        }

        return $response;
    }
}