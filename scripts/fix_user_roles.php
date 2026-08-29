<?php
$base = dirname(__DIR__);
require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;

$mappings = [
    'drrm_officer' => 'mdrrmo',
    'warehouse_staff' => 'lgu_staff',
    'evacuation_manager' => 'evac_manager',
];

foreach ($mappings as $old => $new) {
    $oldRole = Role::where('slug', $old)->first();
    $newRole = Role::where('slug', $new)->first();
    if (!$oldRole || !$newRole) {
        echo "Skipping mapping $old -> $new (missing role)\n";
        continue;
    }
    $count = User::where('role_id', $oldRole->id)->update(['role_id' => $newRole->id]);
    echo "Updated $count users: $old -> $new\n";
}

// Optionally remove old role rows to avoid future confusion
$oldSlugs = array_keys($mappings);
$deleted = Role::whereIn('slug', $oldSlugs)->delete();
echo "Deleted $deleted old role records\n";
