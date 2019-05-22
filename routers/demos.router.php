<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/demos', function (Request $request, Response $response) {

    $demos = new models\Demos();
    $callback = $request->getParam('callback');    

    $response->getBody()->write( ($callback ? $callback . '(' : '') . $demos->fetch() . ($callback ? ');' : '' ) );

    return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
});
