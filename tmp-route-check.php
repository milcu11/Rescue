<?php

require __DIR__ . '/vendor/autoload.php';

try {
    $app = require __DIR__ . '/bootstrap/app.php';

    $ref = new ReflectionClass(Illuminate\Foundation\Support\Providers\RouteServiceProvider::class);
    $prop = $ref->getProperty('alwaysLoadRoutesUsing');
    $prop->setAccessible(true);
    $callback = $prop->getValue();

    echo 'HAS ROUTE CALLBACK: ' . ($callback ? 'YES' : 'NO') . "\n";
    echo 'CALLABLE: ' . (is_callable($callback) ? 'YES' : 'NO') . "\n";

    if (is_callable($callback)) {
        echo 'ROUTE CALLBACK CLASS: ' . get_class($callback) . "\n";
    }

    if ($app->resolved('router')) {
        $router = $app->make('router');
        $routes = $router->getRoutes();
        echo 'ROUTES: ' . count($routes) . "\n";

        foreach ($routes->getRoutes() as $route) {
            $name = $route->getName();
            if ($name && str_contains($name, 'donations.payment')) {
                echo 'MATCH: ' . $name . "\n";
            }
        }
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo $e;
}
