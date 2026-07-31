<?php
require 'vendor/autoload.php';

try {
    $app = require 'bootstrap/app.php';
    echo "bootstrap-ok\n";
} catch (Throwable $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
