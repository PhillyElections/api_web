<?php

// GET index route
$app->get(
    '/admin/{api}',
    function () use ($app) {
        $admin = new models\Admin();
        $content = $admin->getContent();

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
