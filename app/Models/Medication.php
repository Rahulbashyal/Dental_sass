<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'description',
        'common_dosages',
        'side_effects',
        'contraindications',
        'manufacturer',
        'is_active',
    ];

    protected $casts = [
        'common_dosages' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get prescription items using this medication
     */
    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Scope for active medications only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for searching medications
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('generic_name', 'like', "%{$term}%")
              ->orWhere('category', 'like', "%{$term}%");
        });
    }
}
