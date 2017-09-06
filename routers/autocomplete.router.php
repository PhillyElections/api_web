<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/autocomplete/{address}', function (Request $request, Response $response) {
    if (in_array($request->getHeader('host')[0], array('aapis.philadelphiavotes.com', 'www.philadelphiavotes.com', 'philadelphiavotes.com'))) {
        $address = $request->getAttribute('address');

        $referrerAuth = new models\ReferrerAuth();
        $autocomplete = new models\Autocomplete($address);
        d($referferAuth->authenticate());
        $response->getBody()->write($autocomplete->fetch());

        return $response;
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});
