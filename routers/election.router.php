<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/election', function (Request $request, Response $response) {
    // exclude auth
/*    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');
*/
    $callback = $request->getParam('callback'); 

    $model = new models\Election();

    $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetch() . ($callback ? ');' : '' ) );

    return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
});

$app->get('/election/{queried}', function (Request $request, Response $response) {
    // exclude auth
/*    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');
*/

    $callback = $request->getParam('callback'); 
    $queried = $request->getAttribute('queried');

    $model = new models\Election($queried);

    $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetch() . ($callback ? ');' : '' ) );

    return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
});
