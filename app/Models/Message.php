<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'clinic_id',
        'sender_id',
        'patient_id',
        'body',
        'type',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
