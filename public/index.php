<?php

use Core\Kernel;

require dirname(__DIR__).'/vendor/autoload.php';

$app = new Kernel();


$response = $app->run();

\Http\Response\send($response);
