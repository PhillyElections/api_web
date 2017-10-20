<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/indexes/', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');

    if ($referrerAuth->authenticate()) {
        $response->getBody()->write(
            '<h1>Indexes:</h1>'
        );

        return $response->withHeader('Content-Type', 'text/html')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

// federal_house routes
$app->get('/indexes/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\Indexes($queried);

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

$app->get('/indexes/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\Indexes($queried);
        $response->getBody()->write($model->fetchSome());

        return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});
