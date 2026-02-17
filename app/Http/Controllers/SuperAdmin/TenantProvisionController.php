<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;
use App\Jobs\ProvisionTenant;

class TenantProvisionController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::query();
        if ($q = $request->query('q')) {
            $query->where('id', 'like', "%{$q}%")->orWhereRaw("JSON_EXTRACT(data, '$.name') like ?", ["%{$q}%"]);
        }

        $tenants = $query->orderBy('id', 'desc')->paginate(15);

        return view('superadmin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('superadmin.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|max:64',
            'domain' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string',
            'from_address' => 'nullable|email',
            'from_name' => 'nullable|string',
        ]);

        $id = $data['id'];
        $domain = $data['domain'];
        $email = $data['admin_email'];
        $password = $data['admin_password'];

        if (Tenant::find($id)) {
            return back()->withErrors(['id' => 'Tenant id already exists'])->withInput();
        }

        $tenant = Tenant::create(['id' => $id, 'data' => ['name' => $id, 'provision_status' => 'pending']]);

        Domain::create(['domain' => $domain, 'tenant_id' => $tenant->id]);

        // Store tenant mail config
        $mailConfig = [
            'mail' => [
                'default' => 'smtp',
                'mailers' => [
                    'smtp' => [
                        'transport' => 'smtp',
                        'host' => $data['smtp_host'] ?? null,
                        'port' => isset($data['smtp_port']) ? (int) $data['smtp_port'] : null,
                        'encryption' => $data['smtp_encryption'] ?? null,
                        'username' => $data['smtp_username'] ?? null,
                        'password' => $data['smtp_password'] ?? null,
                    ],
                ],
                'from' => [
                    'address' => $data['from_address'] ?? $email,
                    'name' => $data['from_name'] ?? 'Tenant Admin',
                ],
            ],
        ];

        $existing = is_array($tenant->data) ? $tenant->data : (array) ($tenant->data ?? []);
        $existing['config'] = array_merge($existing['config'] ?? [], $mailConfig);
        $tenant->update(['data' => $existing]);

        // dispatch provisioning job (migrations + seeding + admin creation)
        ProvisionTenant::dispatch($tenant->id, $email, $password);

        return redirect()->route('superadmin.tenants.index')->with('status', 'Tenant created and provisioning started');
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        $domain = Domain::where('tenant_id', $tenant->id)->first();
        return view('superadmin.tenants.edit', compact('tenant', 'domain'));
    }

    public function logs($id)
    {
        $tenant = Tenant::findOrFail($id);
        $logs = \App\Models\ProvisioningLog::where('tenant_id', $tenant->id)->orderBy('created_at', 'desc')->paginate(25);
        return view('superadmin.tenants.logs', compact('tenant', 'logs'));
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $data = $request->validate([
            'domain' => 'required|string|max:255',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string',
            'from_address' => 'nullable|email',
            'from_name' => 'nullable|string',
        ]);

        // Update domain (replace first domain)
        $domainModel = Domain::where('tenant_id', $tenant->id)->first();
        if ($domainModel) {
            $domainModel->update(['domain' => $data['domain']]);
        } else {
            Domain::create(['domain' => $data['domain'], 'tenant_id' => $tenant->id]);
        }

        // Update mail config
        $mailConfig = [
            'mail' => [
                'default' => 'smtp',
                'mailers' => [
                    'smtp' => [
                        'transport' => 'smtp',
                        'host' => $data['smtp_host'] ?? null,
                        'port' => isset($data['smtp_port']) ? (int) $data['smtp_port'] : null,
                        'encryption' => $data['smtp_encryption'] ?? null,
                        'username' => $data['smtp_username'] ?? null,
                        'password' => $data['smtp_password'] ?? null,
                    ],
                ],
                'from' => [
                    'address' => $data['from_address'] ?? null,
                    'name' => $data['from_name'] ?? null,
                ],
            ],
        ];

        $existing = is_array($tenant->data) ? $tenant->data : (array) ($tenant->data ?? []);
        $existing['config'] = array_merge($existing['config'] ?? [], $mailConfig);
        $tenant->update(['data' => $existing]);

        return redirect()->route('superadmin.tenants.index')->with('status', 'Tenant updated');
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Delete domains and tenant record.
        $domains = Domain::where('tenant_id', $tenant->id)->get();
        foreach ($domains as $d) {
            $d->delete();
        }

        $tenant->delete();

        // Optionally: remove backups and storage
        $backupPath = storage_path("app/backups/tenants/{$id}");
        if (is_dir($backupPath)) {
            $files = glob("{$backupPath}/*");
            foreach ($files as $f) {
                @unlink($f);
            }
            @rmdir($backupPath);
        }

        return redirect()->route('superadmin.tenants.index')->with('status', 'Tenant deleted (record).');
    }

    public function reprovision(Request $request, $id)
    {
        $data = $request->validate([
            'admin_email' => 'required|email',
            'admin_password' => 'required|string|min:8',
        ]);

        $tenant = Tenant::findOrFail($id);

        $existing = is_array($tenant->data) ? $tenant->data : (array) ($tenant->data ?? []);
        $existing['provision_status'] = 'pending';
        unset($existing['provision_error']);
        $tenant->update(['data' => $existing]);

        ProvisionTenant::dispatch($tenant->id, $data['admin_email'], $data['admin_password']);

        return redirect()->route('superadmin.tenants.edit', $tenant->id)->with('status', 'Re-provisioning started');
    }
}
