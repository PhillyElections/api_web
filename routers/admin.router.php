<?php

// GET index route
$app->get(
    '/admin',
    function ($request, $response, $args) use ($app) {
        $admin = new models\Admin();
        $content = $admin->getContent();
        d($app);
        // $content is segmented into title/header/body
        $app->render(
            'admin.html',
            $content
        );
    }
);

// POST index route
$app->post(
    '/admin/{api}',
    function () use ($app) {
        $admin = new models\Admin();

        $admin->process();
    }
);
