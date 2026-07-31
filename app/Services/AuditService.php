<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public static function log(
        string  $action,
        string  $module,
        ?string $recordLabel = null,
        ?int    $recordId    = null,
        ?array  $oldValues   = null,
        ?array  $newValues   = null,
        ?string $notes       = null
    ): void {
        $user = Auth::user();

        AuditLog::create([
            'user_id'      => $user?->id,
            'user_name'    => $user?->name ?? 'System',
            'user_role'    => $user?->role?->slug ?? 'system',
            'action'       => $action,
            'module'       => $module,
            'record_label' => $recordLabel,
            'record_id'    => $recordId,
            'old_values'   => $oldValues,
            'new_values'   => $newValues,
            'ip_address'   => request()->ip(),
            'notes'        => $notes,
        ]);
    }

    public static function created(string $module, string $label, int $id, array $values): void
    {
        self::log('created', $module, $label, $id, null, $values);
    }

    public static function updated(string $module, string $label, int $id, array $old, array $new): void
    {
        $changed = [];
        foreach ($new as $key => $value) {
            if (isset($old[$key]) && $old[$key] != $value) {
                $changed[$key] = $value;
            }
        }

        if (!empty($changed)) {
            self::log('updated', $module, $label, $id, $old, $changed);
        }
    }

    public static function deleted(string $module, string $label, int $id): void
    {
        self::log('deleted', $module, $label, $id);
    }

    public static function login(string $userName): void
    {
        self::log('login', 'auth', $userName, null, null, null, 'User logged in');
    }

    public static function logout(string $userName): void
    {
        self::log('logout', 'auth', $userName, null, null, null, 'User logged out');
    }
}
