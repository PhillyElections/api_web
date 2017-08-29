<?php

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = ini_get('mysqli.default_host');
$config['db']['user']   = ini_get('mysqli.default_user');
$config['db']['pass']   = ini_get('mysqli.default_pw');
$config['db']['dbname'] = ini_get('mysqli.default_user');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';

$app = new \Slim\App(['settings' => $config]);
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->run();
