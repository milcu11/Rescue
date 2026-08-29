<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private NotificationService $notifications;

    public function __construct(NotificationService $notifications)
    {
        $this->notifications = $notifications;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $notifications = $this->notifications->allForUser($user->id, $user->role?->slug);

        return view('notifications.index', compact('notifications'));
    }

    public function json(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $roleTarget = $user->role?->slug;

        $recent = $this->notifications->recentForUser($userId, $roleTarget, 10);
        $unread = $this->notifications->unreadCountForUser($userId, $roleTarget);

        $payload = $recent->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'link' => $n->link,
                'type' => $n->type,
                'is_read' => (bool) $n->is_read,
                'created_at' => $n->created_at->toDateTimeString(),
                'time_ago' => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $payload,
            'unread' => $unread,
        ]);
    }

    public function markAllRead(Request $request)
    {
        $user = auth()->user();
        $this->notifications->markAllAsRead($user->id, $user->role?->slug);

        return redirect()->route('notifications.index')
            ->with('success', 'All notifications marked as read.');
    }

    public function markRead($id)
    {
        $user = auth()->user();
        $notification = \App\Models\Notification::forUser($user)->findOrFail($id);
        $notification->update(['is_read' => true]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $notification = \App\Models\Notification::forUser(auth()->user())->findOrFail($id);
        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notification removed.');
    }
}
