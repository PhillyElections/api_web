<?php

// GET index route
$app->get(
    '/admin',
    function () use ($app) {
        $admin = new models\Admin();

        $content = $admin->getContent();
        d($content);
        // $content is segmented into title/header/body
        $app->render(
            'admin.html',
            $content
        );
    }
);

// POST index route
$app->post('/admin', function () use ($app) {
    $admin = new models\Admin();

    $admin->process();
});
