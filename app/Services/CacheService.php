<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    public static function cachePatientData($patientId, $data, $minutes = 60)
    {
        Cache::put("patient:{$patientId}", $data, $minutes * 60);
    }

    public static function getCachedPatientData($patientId)
    {
        return Cache::get("patient:{$patientId}");
    }

    public static function cacheAppointments($clinicId, $date, $appointments)
    {
        Cache::put("appointments:{$clinicId}:{$date}", $appointments, 30 * 60);
    }

    public static function getCachedAppointments($clinicId, $date)
    {
        return Cache::get("appointments:{$clinicId}:{$date}");
    }

    public static function invalidatePatientCache($patientId)
    {
        Cache::forget("patient:{$patientId}");
        Cache::tags(['patients'])->flush();
    }

    public static function invalidateClinicCache($clinicId)
    {
        Cache::forget("clinic:{$clinicId}");
        $pattern = "appointments:{$clinicId}:*";
        
        if (config('cache.default') === 'redis') {
            $keys = Redis::keys($pattern);
            if (!empty($keys)) {
                Redis::del($keys);
            }
        }
    }

    public static function cacheQueryResult($key, $callback, $minutes = 60)
    {
        return Cache::remember($key, $minutes * 60, $callback);
    }

    public static function warmupCache()
    {
        // Cache frequently accessed data
        $clinics = \App\Models\Clinic::with('users')->get();
        foreach ($clinics as $clinic) {
            Cache::put("clinic:{$clinic->id}", $clinic, 3600);
        }

        // Cache system settings
        Cache::put('system_settings', config('app'), 3600);
    }
}