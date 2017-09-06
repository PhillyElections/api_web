<?php

$app->get('/autocomplete/{address}', function (Request $request, Response $response) {
    $address = $request->getAttribute('address');
    $autocomplete = new models\Autocomplete($address);

    $response->getBody()->write($autocomplete->fetch());

    return $response;
});
