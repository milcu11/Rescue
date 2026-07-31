<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Route;

$app = require __DIR__ . '/bootstrap/app.php';
Facade::setFacadeApplication($app);

try {
    Route::middleware('api')->prefix('api')->group(__DIR__ . '/routes/api.php');
    $router = $app->make('router');
    echo 'ROUTES: ' . count($router->getRoutes()) . "\n";
    foreach ($router->getRoutes() as $route) {
        echo $route->uri() . ' | ' . implode(',', $route->methods()) . "\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e;
}
