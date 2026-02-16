<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'clinic_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'category'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
