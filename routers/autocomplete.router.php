<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/autocomplete/{address}', function (Request $request, Response $response) {
    if (in_array($request->getHeader('host')[0], array('apis.philadelphiavotes.com', 'www.philadelphiavotes.com', 'philadelphiavotes.com'))) {
        d('haven\'t returned yet', $request->getHeader('host'), in_array($request->getHeader('host')[0], array('apis.philadelphiavotes.com', 'www.philadelphiavotes.com', 'philadelphiavotes.com')));
        exit;

        $address = $request->getAttribute('address');
        $autocomplete = new models\Autocomplete($address);

        $response->getBody()->write($autocomplete->fetch());

        return $response;
    }

    return $response->withStatus('401', 'Unauthorized');
});
