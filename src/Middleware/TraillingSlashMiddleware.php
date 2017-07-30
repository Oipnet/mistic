<?php

namespace App\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TraillingSlashMiddleware implements MiddlewareInterface
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
        $url = $request->getUri()->getPath();
        if (!empty($url) && $url[-1] === '/') {
            $response = new Response();

            return $response
                ->withHeader('Location', substr($url, 0, -1))
                ->withStatus(301);
        }

        return $delegate->process($request);
    }
}