<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
 
$app->get('/pollingplaces/fulllist/', function (Request $request, Response $response) {
    $response->getBody()->write("We'll show a list of all polling places.");

    return $response;
});

$app->get('/pollingplaces/wardlist/{ward}', function (Request $request, Response $response) {
    $ward = $request->getAttribute('ward');

    $response->getBody()->write("We'll show a list of polling places for the ward: $ward.");

    return $response;
});

$app->get('/pollingplaces/{precinct}', function (Request $request, Response $response) {
    $precinct = $request->getAttribute('precinct');

    $pollingplaces = new models\Pollingplaces($precinct);
    $response->getBody()->write($pollingplaces->fetch());

    return $response;
});
