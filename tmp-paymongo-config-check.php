<?php
require __DIR__ . '/vendor/autoload.php';

try {
    $app = require __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo 'cwd=' . getcwd() . PHP_EOL;
    echo 'basePath=' . $app->basePath() . PHP_EOL;
    echo 'envPath=' . $app->environmentPath() . PHP_EOL;
    echo 'envFilePath=' . $app->environmentFilePath() . PHP_EOL;
    echo 'envExists=' . (is_file($app->environmentFilePath()) ? 'yes' : 'no') . PHP_EOL;
    echo 'APP_ENV=' . (getenv('APP_ENV') ?: 'NULL') . PHP_EOL;
    echo 'PAYMONGO_SECRET=' . (getenv('PAYMONGO_SECRET_KEY') ?: 'NULL') . PHP_EOL;
    echo 'env func=' . (env('PAYMONGO_SECRET_KEY') ?: 'NULL') . PHP_EOL;
    echo 'config secret=' . (config('paymongo.secret_key') ?: 'NULL') . PHP_EOL;
    echo 'all env=' . var_export(
        array_filter($_ENV, fn($k)=> str_contains((string)$k,'PAYMONGO') || $k==='APP_ENV'), true
    ) . PHP_EOL;
    echo 'server env=' . var_export(
        array_filter($_SERVER, fn($k)=> str_contains((string)$k,'PAYMONGO') || $k==='APP_ENV'), true
    ) . PHP_EOL;
    echo 'Env repo=' . var_export(Illuminate\Support\Env::getRepository()->all(), true) . PHP_EOL;
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
