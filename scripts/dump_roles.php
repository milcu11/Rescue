<?php
$base = dirname(__DIR__);
require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

$users = User::with('role')->get();
foreach ($users as $u) {
    echo "user: {$u->id} | name: {$u->name} | email: {$u->email} | role_slug: {$u->role->slug} | role_name: {$u->role->name}\n";
}

$roles = Role::all();
foreach ($roles as $r) {
    echo "role: {$r->id} | slug: {$r->slug} | name: {$r->name}\n";
}
