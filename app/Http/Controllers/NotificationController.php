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
     * Mark all unread notifications as read.
     */
    public function markRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
