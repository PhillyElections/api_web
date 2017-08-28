<?php

// Get user
$app->get(
    '/autocomplete',
    function () use ($app) {
        $autocomplete = new models\Autocomplete();

        //$app->contentType('application/json');
        echo $autocomplete->fetch();
    }
);

$app->post(
    '/autocomplete',
    function () use ($app) {
        $autocomplete = new models\Autocomplete();

        $app->contentType('application/json');
        echo $autocomplete->fetch();
    }
);
