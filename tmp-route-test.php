<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;

$app = require __DIR__ . '/bootstrap/app.php';
Facade::setFacadeApplication($app);

Route::middleware('api')->prefix('api')->group(function () {
    Route::any('test', fn () => 'ok');
});

$router = $app->make('router');
echo 'ROUTES: ' . count($router->getRoutes()) . "\n";
foreach ($router->getRoutes() as $route) {
    echo $route->uri() . ' | ' . implode(',', $route->methods()) . "\n";
}
