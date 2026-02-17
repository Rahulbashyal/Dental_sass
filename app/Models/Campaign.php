<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'target_audience',
        'budget',
        'total_sent',
        'total_opened',
        'total_clicked',
        'total_converted',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'target_audience' => 'array',
        'budget' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function emailLogs(): HasMany
    {
        return $this->hasMany(EmailLog::class);
    }
}
