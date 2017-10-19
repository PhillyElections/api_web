<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/shapes/', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');

    if ($referrerAuth->authenticate()) {
        $response->getBody()->write(
            '<h1>Available Shape Services:</h1>' .
            '<ul>' .
            '  <li><a href="/shapes/us_congress/">US Congressional</a> (/shapes/us_congress/[geoid])</li>' .
            '</ul>'
        );

        return $response->withHeader('Content-Type', 'text/html')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

$app->get('/shapes/us_congress/{geoid}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');

    if ($referrerAuth->authenticate()) {
        $geoid = $request->getAttribute('geoid');

        $model = new models\UsCongress($geoid);
        dd($model);
        $response->getBody()->write($model->fetch());

        return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});
