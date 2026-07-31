<?php

use Illuminate\Support\Facades\Facade;

require __DIR__ . '/vendor/autoload.php';

try {
    $app = require __DIR__ . '/bootstrap/app.php';
    Facade::setFacadeApplication($app);
    $app->boot();
    $router = $app->make('router');
    echo 'ROUTES: ' . count($router->getRoutes()) . "\n";
    foreach ($router->getRoutes() as $route) {
        echo $route->uri() . ' | ' . implode(',', $route->methods()) . "\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo $e;
}
