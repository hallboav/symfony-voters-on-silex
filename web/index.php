<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\ControllerApplication;
use App\Security\Core\Authorization\Voter\AdulthoodVoter;
use App\Security\Core\User\InMemoryUserProvider;
use App\Security\Core\User\User;
use Silex\Provider\SecurityServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

$app = new ControllerApplication();

$providerKey = 'default';

$app->register(new SecurityServiceProvider(), [
    'security.firewalls' => [
        $providerKey => [
            'http' => true
        ]
    ]
]);

$app->extend('security.voters', function ($voters, $app) {
    $voters[] = new AdulthoodVoter();
    return $voters;
});

$app['security.user_provider.' . $providerKey] = function ($app) {
    return new InMemoryUserProvider([
        new User(
            '17yo',
            '$2y$13$JD7JpZjdYq4xrEo5qnms7.ZJTabxNm1ZvQKXup248Y2c3SucBlk32', // 17yo
            17,
            ['ROLE_USER']
        ),
        new User(
            '19yo',
            '$2y$13$ZV2VRqYf1ds8t9q2mMFs0OAR5QbBqPm3zcSINNCCJ.Jeps8.5ayq6', // 19yo
            19,
            ['ROLE_USER']
        )
    ]);
};

$app->error(function (\Exception $exception, Request $request, $code) {
    $message = $exception->getMessage();
    if ($exception instanceof HttpException) {
        return Response::create($message, $exception->getStatusCode(), $exception->getHeaders());
    }

    return Response::create($message, $code);
});

$app->get('/', function () use ($app) {
    $app->denyAccessUnlessGranted(AdulthoodVoter::ADULTHOOD);
    return Response::create('XXX');
});

$app->run();
