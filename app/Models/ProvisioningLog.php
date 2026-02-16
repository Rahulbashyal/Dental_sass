<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvisioningLog extends Model
{
    protected $connection = 'central';
    protected $table = 'provisioning_logs';

    protected $fillable = [
        'tenant_id',
        'level',
        'message',
    ];
}
