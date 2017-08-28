<?php

// GET index route
$app->get('/', function () use ($app) {
    $app->render('index.html', array('hello' => 'Move along, please...', 'title'=> 'Nothing to see here.'));
});
