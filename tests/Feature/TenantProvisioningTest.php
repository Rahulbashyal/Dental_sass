<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use App\Jobs\ProvisionTenant;
use Stancl\Tenancy\Database\Models\Tenant;

class TenantProvisioningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Basic test to ensure tenant creation dispatches the provisioning job.
     * This is a scaffolded feature test — adjust middleware/auth as needed.
     */
    public function test_tenant_creation_dispatches_provision_job()
    {
        Bus::fake();

        // Skip middleware in test to simplify role checks; replace with actingAs(...) if preferred
        $this->withoutMiddleware();

        $response = $this->post(route('superadmin.tenants.store'), [
            'id' => 'tenant-test',
            'domain' => 'tenant-test.example',
            'admin_email' => 'admin@tenant-test.example',
            'admin_password' => 'secret1234',
        ]);

        $response->assertRedirect(route('superadmin.tenants.index'));

        // Confirm tenant record created (tenants table is used by stancl/tenancy)
        $this->assertDatabaseHas('tenants', ['id' => 'tenant-test']);

        // Assert provisioning job was dispatched for the tenant
        Bus::assertDispatched(ProvisionTenant::class, function ($job) {
            return $job->tenantId === 'tenant-test' && $job->adminEmail === 'admin@tenant-test.example';
        });
    }
}
