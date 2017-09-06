<?php

// GET index route
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Move allong... Nothing to see here.');

    return $response;
});
