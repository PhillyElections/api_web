<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/pollingplaces/wardlist/{ward}', function (Request $request, Response $response) {
    $precinct = $request->getAttribute('ward');

//    $pollingplaces = new models\Pollingplaces($precinct);
    $response->getBody()->write("We'll show a list of polling places for the ward " . $precinct);

    return $response;
});

$app->get('/pollingplaces/{precinct}', function (Request $request, Response $response) {
    $precinct = $request->getAttribute('precinct');

    $pollingplaces = new models\Pollingplaces($precinct);
    $response->getBody()->write($pollingplaces->fetch());

    return $response;
});
