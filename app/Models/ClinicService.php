<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ClinicService extends Model
{
    protected $fillable = [
        'clinic_id',
        'name',
        'slug',
        'description',
        'short_description',
        'duration_minutes',
        'price',
        'show_pricing',
        'category',
        'featured_image',
        'before_after_images',
        'seo_title',
        'seo_description',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'show_pricing' => 'boolean',
        'is_active' => 'boolean',
        'before_after_images' => 'array',
        'duration_minutes' => 'integer',
        'display_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    /**
     * Get the clinic that owns the service
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Scope to get only active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}
