<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(15);
        
        // Mark all as read when they visit this page
        auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Remove the specified notification from storage.
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification successfully deleted.');
    }

    /**
     * Remove all notifications from storage.
     */
    public function clear()
    {
        auth()->user()->notifications()->delete();

        return redirect()->back()->with('success', 'All notification history successfully cleared.');
    }
    /**
     * Mark a single notification as read (used by the per-row
     * "Mark as read" button on the Notifications page).
     */
    public function markOneRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all unread notifications as read (JSON — used by the header bell via JS).
     */
    public function markRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * Mark all unread notifications as read (form submit — used by the
     * "Mark all as read" button on the Notifications page).
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
