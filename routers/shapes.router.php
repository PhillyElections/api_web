<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/shapes/', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');

    if ($referrerAuth->authenticate()) {
        $response->getBody()->write(
            '<h1>Available pre-2018 Shape Services:</h1>' .
            '<ul>' .
            '  <li><a href="/shapes/city_district/">City Council Districts</a> (/shapes/city_district/[district])</li>' .
            '  <li><a href="/shapes/city_division/">City Divisions</a> (/shapes/city_division/[division])</li>' .
            '  <li><a href="/shapes/city_ward/">City Wards</a> (/shapes/city_ward/[ward])</li>' .
            '  <li><a href="/shapes/state_senate/">State Senate</a> (/shapes/state_senate/[district])</li>' .
            '  <li><a href="/shapes/state_house/">State Representative</a> (/shapes/state_house/[district])</li>' .
            '  <li><a href="/shapes/federal_house/">US Congressional</a> (/shapes/federal_house/[geoid])</li>' .
            '</ul>' .
            '<p>A JSONP response may be had by including \'?callback=...\' at the end of your query string.'
        );

        return $response->withHeader('Content-Type', 'text/html')->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

// city_district routes
$app->get('/shapes/city_district/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\ShapeCityDistrict($queried);

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

$app->get('/shapes/city_district/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\ShapeCityDistrict($queried);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchSome() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

// city_division routes
$app->get('/shapes/city_division/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback');

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\ShapeCityDivision($queried);

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

$app->get('/shapes/city_division/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\ShapeCityDivision($queried);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchSome() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

// city_ward routes
$app->get('/shapes/city_ward/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\ShapeCityWard($queried);

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

$app->get('/shapes/city_ward/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\ShapeCityWard($queried);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchSome() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

// state_senate routes
$app->get('/shapes/state_senate/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\ShapeStateSenate($queried);

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

$app->get('/shapes/state_senate/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\ShapeStateSenate($queried);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchSome() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

// state_house routes
$app->get('/shapes/state_house/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\ShapeStateHouse($queried);

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

$app->get('/shapes/state_house/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\ShapeStateHouse($queried);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchSome() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});

// federal_house routes
$app->get('/shapes/federal_house/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        $model = new models\ShapeFederalHouse($queried);

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

$app->get('/shapes/federal_house/some/{queried}', function (Request $request, Response $response) {
    $referrer = $this->request->getHeader('host')[0];
    $referrerAuth = new models\ReferrerAuth($referrer, 'shapes');
    $callback = $request->getParam('callback'); 

    if ($referrerAuth->authenticate()) {
        $queried = $request->getAttribute('queried');

        d($queried);
        exit;

        $model = new models\ShapeFederalHouse($queried);
        $response->getBody()->write( ($callback ? $callback . '(' : '') . $model->fetchSome() . ($callback ? ');' : '' ) );

        return $response->withHeader('Content-Type', ($callback? 'application/javascript': 'application/json'))->withHeader('Access-Control-Allow-Origin', '*');
    }

    $response->getBody()->write('<h1>401: Unauthorized</h1>');

    return $response->withStatus(401);
});