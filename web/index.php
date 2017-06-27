<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\ControllerApplication;

$app = new ControllerApplication();

$app->error(function (\Exception $exception, Request $request, $code) {
    $message = $exception->getMessage();
    if ($exception instanceof HttpException) {
        return Response::create($message, $exception->getStatusCode(), $exception->getHeaders());
    }

    return Response::create($message, $code);
});

$app->run();
