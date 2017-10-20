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

// federal_house routes
$app->get('/shapes/federal_house/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\UsCongress($queried);

        if (in_array($queried, array('all', 'all/'))) {
            // the rare and costly case
            d($queried);
            exit;
            $response->getBody()->write($model->fetchAll());
        } elseif (is_numeric($queried)) {
            // the typical case
            $response->getBody()->write($model->fetch());
        }

        return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

$app->get('/shapes/federal_house/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\UsCongress($queried);
        $response->getBody()->write($model->fetchSome());

        return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});
