<?php
/**
 * Created by PhpStorm.
 * User: arnaudp
 * Date: 29/07/17
 * Time: 14:28
 */

namespace App\Middleware;


use function GuzzleHttp\Psr7\stream_for;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GoogleAnalyticsMiddleware implements MiddlewareInterface
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

        if ($request->getAttribute(FormatNegociatorMiddleware::KEY) !== 'html') {

            return $response;
        }

        $body = (string) $response->getBody();
        $tag = "<ga></ga>";
        $body = str_replace('</body>', $tag.'</body>', $body);
        $body = stream_for($body);

        return $response->withBody($body);
    }
}