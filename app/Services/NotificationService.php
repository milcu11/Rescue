<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public static function sendToUser(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }

    public static function sendToRole(
        string $roleSlug,
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): Notification {
        return Notification::create([
            'role_target' => $roleSlug,
            'type'        => $type,
            'title'       => $title,
            'message'     => $message,
            'link'        => $link,
        ]);
    }

    public static function sendToSuppliers(
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): Notification {
        return self::sendToRole('supplier', $type, $title, $message, $link);
    }

    public static function sendToVolunteers(
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): Notification {
        return self::sendToRole('volunteer', $type, $title, $message, $link);
    }

    public static function sendToResidents(
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): Notification {
        return self::sendToRole('resident', $type, $title, $message, $link);
    }

    public static function sendToAll(
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): Notification {
        return Notification::create([
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }

    public static function lowStock(string $itemName, int $quantity, string $unit, string $link): Notification
    {
        return self::sendToRole(
            'lgu_staff',
            'low_stock',
            'Low Stock Alert',
            "{$itemName} is running low — only {$quantity} {$unit} remaining.",
            $link
        );
    }

    public static function centerFull(string $centerName, string $link): Notification
    {
        return self::sendToRole(
            'mdrrmo',
            'center_full',
            'Evacuation Center Full',
            "{$centerName} has reached full capacity.",
            $link
        );
    }

    public static function newDonation(string $donorName, string $code, string $link): Notification
    {
        return self::sendToRole(
            'mdrrmo',
            'new_donation',
            'New Donation Received',
            "Donation {$code} from {$donorName} has been recorded.",
            $link
        );
    }

    public static function distributionRecorded(
        string $operationName,
        string $centerName,
        string $link
    ): Notification {
        $notification = self::sendToRole(
            'mdrrmo',
            'distribution_recorded',
            'Distribution Recorded',
            "Items distributed to {$centerName} under operation: {$operationName}.",
            $link
        );

        self::sendToRole(
            'lgu_staff',
            'distribution_recorded',
            'Distribution Recorded',
            "Items distributed to {$centerName} under operation: {$operationName}.",
            $link
        );

        return $notification;
    }

    public function unreadCountForUser(?int $userId = null, ?string $roleTarget = null): int
    {
        $query = Notification::query()->where('is_read', false);

        if ($userId) {
            $query->where(function ($sub) use ($userId) {
                $sub->where('user_id', $userId)
                    ->orWhereNull('user_id');
            });
        }

        if ($roleTarget) {
            $query->where(function ($sub) use ($roleTarget) {
                $sub->where('role_target', $roleTarget)
                    ->orWhereNull('role_target');
            });
        }

        return $query->count();
    }

    public function recentForUser(?int $userId = null, ?string $roleTarget = null, int $limit = 5)
    {
        $query = Notification::query();

        if ($userId) {
            $query->where(function ($sub) use ($userId) {
                $sub->where('user_id', $userId)
                    ->orWhereNull('user_id');
            });
        }

        if ($roleTarget) {
            $query->where(function ($sub) use ($roleTarget) {
                $sub->where('role_target', $roleTarget)
                    ->orWhereNull('role_target');
            });
        }

        return $query->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function markAsRead(int $id): bool
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return false;
        }

        $notification->update(['is_read' => true]);

        return true;
    }

    public function markAllAsRead(?int $userId = null, ?string $roleTarget = null): int
    {
        $query = Notification::query()->where('is_read', false);

        if ($userId) {
            $query->where(function ($sub) use ($userId) {
                $sub->where('user_id', $userId)
                    ->orWhereNull('user_id');
            });
        }

        if ($roleTarget) {
            $query->where(function ($sub) use ($roleTarget) {
                $sub->where('role_target', $roleTarget)
                    ->orWhereNull('role_target');
            });
        }

        return $query->update(['is_read' => true]);
    }

    public function allForUser(?int $userId = null, ?string $roleTarget = null): LengthAwarePaginator
    {
        $query = Notification::query();

        if ($userId) {
            $query->where(function ($sub) use ($userId) {
                $sub->where('user_id', $userId)
                    ->orWhereNull('user_id');
            });
        }

        if ($roleTarget) {
            $query->where(function ($sub) use ($roleTarget) {
                $sub->where('role_target', $roleTarget)
                    ->orWhereNull('role_target');
            });
        }

        return $query->orderByDesc('created_at')->paginate(15);
    }
}
