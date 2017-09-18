<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/autocomplete/{address}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'autocomplete');

    if ($referrerAuth->authenticate()) {
        $address = $request->getAttribute('address');

        $autocomplete = new models\Autocomplete($address);
        $response->getBody()->write($autocomplete->fetch());

        return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});
