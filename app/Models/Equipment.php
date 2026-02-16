<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $fillable = [
        'clinic_id', 
        'name', 
        'model', 
        'serial_number', 
        'purchase_date', 
        'warranty_expiry', 
        'last_maintenance_at', 
        'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'last_maintenance_at' => 'datetime',
    ];
}
