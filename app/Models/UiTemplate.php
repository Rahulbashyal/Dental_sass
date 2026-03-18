<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_image',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'json',
    ];
}
