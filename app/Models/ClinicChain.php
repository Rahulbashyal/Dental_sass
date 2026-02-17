<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicChain extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'settings', 'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    public function clinics(): HasMany
    {
        return $this->hasMany(Clinic::class, 'chain_id');
    }

    public function mainLocation()
    {
        return $this->clinics()->where('is_main_location', true)->first();
    }

    public function totalPatients()
    {
        return $this->clinics()->withCount('patients')->get()->sum('patients_count');
    }

    public function totalAppointments()
    {
        return $this->clinics()->withCount('appointments')->get()->sum('appointments_count');
    }
}
