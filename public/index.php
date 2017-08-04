<?php

use Core\Kernel;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require dirname(__DIR__).'/vendor/autoload.php';

$app = new Kernel();


$response = $app->run();

\Http\Response\send($response);
