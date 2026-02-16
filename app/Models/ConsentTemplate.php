<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentTemplate extends Model
{
    protected $fillable = [
        'clinic_id',
        'title',
        'content',
        'is_active'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
