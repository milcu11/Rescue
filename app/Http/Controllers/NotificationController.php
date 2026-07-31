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
        $userId = auth()->id();
        $notifications = $this->notifications->allForUser($userId, null);

        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead(Request $request)
    {
        $userId = auth()->id();
        $this->notifications->markAllAsRead($userId, null);

        return redirect()->route('notifications.index')
            ->with('success', 'All notifications marked as read.');
    }

    public function markRead($id)
    {
        $this->notifications->markAsRead($id);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $notification = \App\Models\Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notification removed.');
    }
}
