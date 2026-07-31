<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ApiNotificationController extends Controller
{
    private NotificationService $notifications;

    public function __construct(NotificationService $notifications)
    {
        $this->notifications = $notifications;
    }

    public function index(Request $request)
    {
        $user = auth()->guard('api')->user();

        return response()->json([
            'success' => true,
            'data'    => $this->notifications->recentForUser($user->id, $user->role->slug, 50),
        ]);
    }

    public function markRead(int $id)
    {
        $updated = $this->notifications->markAsRead($id);

        if (! $updated) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }
}
