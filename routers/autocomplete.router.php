<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/autocomplete/{address}', function (Request $request, Response $response) {
    $referrerAuth = new models\ReferrerAuth($request);

    if ($referrerAuth->authenticate()) {
        $address = $request->getAttribute('address');

        $autocomplete = new models\Autocomplete($address, 'autocomplete');
        $response->getBody()->write($autocomplete->fetch());

        return $response;
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});
