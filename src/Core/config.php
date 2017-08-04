<?php

use App\Middleware\RouterMiddleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

return [
    ResponseInterface::class => \DI\object(Response::class)->constructor()->lazy(),
    RouterMiddleware::class => \DI\object()->constructor(\DI\get(ResponseInterface::class))->lazy(),
];