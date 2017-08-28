<?php

// GET index route
$app->get(
    '/admin/{api}',
    function () use ($app) {
        $request = new Request();
        $admin = new models\Admin();
        $api = $request->getAttribute('name');
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
    '/admin',
    function () use ($app) {
        $admin = new models\Admin();

        $admin->process();
    }
);
