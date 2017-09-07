<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// GET index route
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Move allong... Nothing to see here.');

    return $response;
});
