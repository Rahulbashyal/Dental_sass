<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenantService
{
    /**
     * Provision a new tenant for a clinic.
     */
    public function provision(Clinic $clinic, array $options = []): Tenant
    {
        Log::info("Starting provisioning for clinic: {$clinic->name}");

        $tenantId = $options['id'] ?? $clinic->slug;

        // 1. Create Tenant Record
        $tenant = Tenant::create([
            'id' => $tenantId,
            'clinic_id' => $clinic->id,
            'data' => array_merge([
                'name' => $clinic->name,
                'email' => $clinic->email,
            ], $options['data'] ?? []),
        ]);

        // 2. Create Domain mapping
        $domain = $options['domain'] ?? ($tenantId . '.' . config('tenancy.central_domains')[0]);
        $tenant->domains()->create(['domain' => $domain]);

        Log::info("Tenant provisioned: {$tenantId} on domain {$domain}");

        return $tenant;
    }
}
