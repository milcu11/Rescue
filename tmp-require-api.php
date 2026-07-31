<?php
require __DIR__.'/vendor/autoload.php';
use Illuminate\Support\Facades\Facade;

$app = require __DIR__.'/bootstrap/app.php';
Facade::setFacadeApplication($app);

$app->boot();

require __DIR__.'/routes/api.php';

$router = $app->make('router');
echo 'ROUTES: '.count($router->getRoutes())."\n";
foreach ($router->getRoutes() as $route) {
    echo $route->uri().' | '.implode(',', $route->methods())."\n";
}
