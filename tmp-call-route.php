<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$ref = new ReflectionClass(Illuminate\Foundation\Support\Providers\RouteServiceProvider::class);
$prop = $ref->getProperty('alwaysLoadRoutesUsing');
$prop->setAccessible(true);
$callback = $prop->getValue();

if (! is_callable($callback)) {
    echo "NO CALLBACK\n";
    exit(1);
}

echo "CALLBACK OK\n";

try {
    $app->call($callback);
    echo "CALL success\n";
    $router = $app->make('router');
    echo 'ROUTES: ' . count($router->getRoutes()) . "\n";
    foreach ($router->getRoutes() as $route) {
        echo $route->uri() . ' | ' . implode(',', $route->methods()) . "\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo $e;
}
