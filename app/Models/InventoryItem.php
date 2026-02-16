<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{
    protected $fillable = [
        'clinic_id', 
        'category_id', 
        'name', 
        'sku', 
        'description', 
        'unit', 
        'min_stock_level', 
        'current_stock', 
        'unit_price'
    ];

    protected $casts = [
        'min_stock_level' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }
}
