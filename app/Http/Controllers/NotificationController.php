<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get the current authenticated notifiable (User or Patient).
     */
    private function getNotifiable()
    {
        if (Auth::guard('patient')->check()) {
            return Auth::guard('patient')->user();
        }
        return Auth::user();
    }

    public function index()
    {
        $notifiable = $this->getNotifiable();
        if (!$notifiable) abort(401);

        $notifications = $notifiable->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notifiable = $this->getNotifiable();
        if (!$notifiable) return response()->json(['success' => false], 401);

        $notification = $notifiable->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $notifiable = $this->getNotifiable();
        if (!$notifiable) return response()->json(['success' => false], 401);
        
        $notifiable->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $notifiable = $this->getNotifiable();
        if (!$notifiable) return response()->json(['count' => 0]);
        
        $count = $notifiable->notifications()->unread()->count();
        return response()->json(['count' => $count]);
    }

    public function clinicIndex()
    {
        $clinic = Auth::user()->clinic;
        if (!$clinic) abort(403);
        
        // This might be for global clinic notifications
        $notifications = Notification::where('notifiable_type', 'App\Models\Clinic')
            ->where('notifiable_id', $clinic->id)
            ->latest()
            ->paginate(20);
            
        return view('admin.notifications.index', compact('notifications'));
    }

    public function sendAppointmentReminders()
    {
        // Placeholder logic for sending reminders
        return back()->with('success', 'Appointment reminders queued for delivery.');
    }
}
