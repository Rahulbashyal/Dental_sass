<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function clinicIndex()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }
        
        Notification::where('user_id', Auth::id())->whereNull('read_at')->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        
        $count = Notification::where('user_id', Auth::id())->unread()->count();
        return response()->json(['count' => $count]);
    }
}
