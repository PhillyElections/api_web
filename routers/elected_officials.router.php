<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/elected_officials/', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {

        $electedOfficials = new models\ElectedOfficials();
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $electedOfficials->fetch() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');

    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

$app->get('/elected_officials/by_level/{level}', function (Request $request, Response $response) {
    $level = $request->getAttribute('level');
    $response->getBody()->write("We'll show a list of elected officials by level: $level.");

    return $response;
});

$app->get('/elected_officials/by_office/{office}', function (Request $request, Response $response) {
    $precinct = $request->getAttribute('precinct');
    $electedOfficials = new models\ElectedOfficials($precinct);
    $callback = $request->getParam('callback');    

    $response->getBody()->write( ($callback ? $callback . '(' : '') . $electedOfficials->fetch() . ($callback ? ');' : '' ) );

    return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
});

$app->get('/elected_officials/by_office/{office}/{district}', function (Request $request, Response $response) {
    $precinct = $request->getAttribute('precinct');
    $electedOfficials = new models\ElectedOfficials($precinct);
    $callback = $request->getParam('callback');    

    $response->getBody()->write( ($callback ? $callback . '(' : '') . $electedOfficials->fetch() . ($callback ? ');' : '' ) );

    return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
});
