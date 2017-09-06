<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/autocomplete/{address}', function (Request $request, Response $response) {
    $address = $request->getAttribute('address');
    $autocomplete = new models\Autocomplete($address);

    $response->getBody()->write($autocomplete->fetch());
    d($request, $response);
});
