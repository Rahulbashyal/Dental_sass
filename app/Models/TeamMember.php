<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    protected $fillable = [
        'clinic_id',
        'user_id',
        'name',
        'title',
        'specialization',
        'bio',
        'photo',
        'education',
        'experience_years',
        'languages',
        'available_days',
        'email',
        'phone',
        'social_links',
        'is_featured',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'available_days' => 'array',
        'social_links' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'experience_years' => 'integer',
        'display_order' => 'integer',
    ];

    /**
     * Get the clinic that owns the team member
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the user associated with this team member (if linked)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active team members
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get featured team members
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}
