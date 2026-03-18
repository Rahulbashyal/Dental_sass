<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'clinic_id',
        'branch_id',
        'phone',
        'photo',
        'specialization',
        'bio',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'website',
        'social_links',
        'is_active',
        'email_verified_at',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'social_links' => 'json',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    public function isClinicAdmin()
    {
        return $this->hasRole('clinic_admin');
    }


    public function sentEmails()
    {
        return $this->hasMany(Email::class, 'sender_id');
    }
}