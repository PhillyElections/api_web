<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/old_indexes/', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');
    $callback = $request->getParam('callback'); 

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
$app->get('/old_indexes/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\OldIndexes($queried);

        if (in_array($queried, array('all', 'all/'))) {
            // the rare and costly case
            d($queried);
            exit;
            $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchAll() . ($callback ? ');' : '' ) );
        } elseif (is_numeric($queried)) {
            // the typical case
            $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetch() . ($callback ? ');' : '' ) );
        }

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

$app->get('/old_indexes/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'indexes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\OldIndexes($queried);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchSome() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});