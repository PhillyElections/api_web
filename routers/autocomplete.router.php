<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/autocomplete/{address}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'autocomplete');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $address = $request->getAttribute('address');

        $model = new models\Autocomplete($address);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetch() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});