<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Facade;

$app = require __DIR__ . '/bootstrap/app.php';
Facade::setFacadeApplication($app);

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
    echo "CALL SUCCESS\n";
    if ($app->resolved('router')) {
        $router = $app->make('router');
        echo 'ROUTES: ' . count($router->getRoutes()) . "\n";
        foreach ($router->getRoutes() as $route) {
            echo $route->uri() . ' | ' . implode(',', $route->methods()) . "\n";
        }
    } else {
        echo 'ROUTER NOT RESOLVED\n';
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e;
}
