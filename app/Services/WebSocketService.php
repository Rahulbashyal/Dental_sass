<?php

namespace App\Services;

use Illuminate\Support\Facades\Broadcast;

class WebSocketService
{
    public static function sendNotification($userId, $message, $type = 'info')
    {
        Broadcast::channel("user.{$userId}", function ($user) use ($userId) {
            return $user->id === (int) $userId;
        });

        broadcast(new \App\Events\NotificationSent($userId, $message, $type));
    }

    public static function sendAppointmentUpdate($clinicId, $appointment)
    {
        broadcast(new \App\Events\AppointmentUpdated($clinicId, $appointment));
    }

    public static function sendSystemAlert($message, $level = 'warning')
    {
        broadcast(new \App\Events\SystemAlert($message, $level));
    }
}