<?php

// GET index route
$app->get('/', function (Request $request, Response $response) {
    d($this, $request, $response, 1);
    exit;
    $response->getBody()->write('Move allong... Nothing to see here.');

    return $response;
});
