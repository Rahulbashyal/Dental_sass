<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    protected $fillable = [
        'sender_id', 'recipients', 'subject', 'body', 'attachments', 
        'status', 'sent_at', 'clinic_id'
    ];

    protected $casts = [
        'recipients' => 'array',
        'attachments' => 'array',
        'sent_at' => 'datetime'
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}