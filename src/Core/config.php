<?php

use App\Middleware\RouterMiddleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

return [
    RequestInterface::class => ServerRequest::fromGlobals(),
    ResponseInterface::class => \DI\object(Response::class)->constructor()->lazy(),
    RouterMiddleware::class => \DI\object()->constructor(\DI\get(ResponseInterface::class))->lazy(),
];
