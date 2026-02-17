<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Define permissions by category
        $permissions = [
            'Patient Management' => [
                'view_patients' => 'View patient list and details',
                'create_patients' => 'Add new patients to the system',
                'edit_patients' => 'Edit patient information',
                'delete_patients' => 'Delete patient records',
                'export_patients' => 'Export patient data',
            ],
            'Appointment Management' => [
                'view_appointments' => 'View appointment schedules',
                'create_appointments' => 'Book new appointments',
                'edit_appointments' => 'Modify appointment details',
                'cancel_appointments' => 'Cancel appointments',
                'reschedule_appointments' => 'Reschedule appointments',
                'manage_waitlist' => 'Manage waiting list',
            ],
            'Financial Management' => [
                'view_invoices' => 'View invoices and billing',
                'create_invoices' => 'Generate new invoices',
                'edit_invoices' => 'Modify invoice details',
                'delete_invoices' => 'Delete invoices',
                'mark_payments' => 'Mark invoices as paid',
                'view_reports' => 'Access financial reports',
                'export_reports' => 'Export financial data',
            ],
            'Communication' => [
                'send_reminders' => 'Send appointment reminders',
                'send_notifications' => 'Send general notifications',
                'manage_templates' => 'Manage message templates',
            ],
            'Staff Management' => [
                'view_staff' => 'View staff members',
                'create_staff' => 'Add new staff members',
                'edit_staff' => 'Edit staff information',
                'delete_staff' => 'Remove staff members',
                'assign_roles' => 'Assign roles to staff',
            ],
            'System Administration' => [
                'manage_roles' => 'Create and manage roles',
                'manage_permissions' => 'Assign permissions to roles',
                'view_analytics' => 'Access system analytics',
                'manage_settings' => 'Configure system settings',
                'manage_clinic' => 'Manage clinic information',
            ],
        ];

        // Create permissions
        foreach ($permissions as $category => $categoryPermissions) {
            foreach ($categoryPermissions as $name => $description) {
                Permission::firstOrCreate([
                    'name' => $name,
                    'category' => $category,
                    'description' => $description,
                ]);
            }
        }

        // Create default roles with permissions
        $this->createDefaultRoles();
    }

    private function createDefaultRoles()
    {
        // Superadmin - All permissions
        $superadmin = Role::firstOrCreate(['name' => 'superadmin'], [
            'display_name' => 'Super Administrator',
            'description' => 'Full system access'
        ]);
        if (!$superadmin->display_name) {
            $superadmin->update([
                'display_name' => 'Super Administrator',
                'description' => 'Full system access'
            ]);
        }
        $superadmin->syncPermissions(Permission::all());

        // Clinic Admin - All clinic permissions
        $clinicAdmin = Role::firstOrCreate(['name' => 'clinic_admin'], [
            'display_name' => 'Clinic Administrator',
            'description' => 'Full clinic management access'
        ]);
        if (!$clinicAdmin->display_name) {
            $clinicAdmin->update([
                'display_name' => 'Clinic Administrator',
                'description' => 'Full clinic management access'
            ]);
        }
        $clinicAdmin->syncPermissions([
            'view_patients', 'create_patients', 'edit_patients', 'export_patients',
            'view_appointments', 'create_appointments', 'edit_appointments', 'cancel_appointments', 'reschedule_appointments', 'manage_waitlist',
            'view_invoices', 'create_invoices', 'edit_invoices', 'mark_payments', 'view_reports', 'export_reports',
            'send_reminders', 'send_notifications', 'manage_templates',
            'view_staff', 'create_staff', 'edit_staff', 'assign_roles',
            'view_analytics', 'manage_settings', 'manage_clinic'
        ]);

        // Receptionist - Front desk operations
        $receptionist = Role::firstOrCreate(['name' => 'receptionist'], [
            'display_name' => 'Receptionist',
            'description' => 'Front desk and appointment management'
        ]);
        if (!$receptionist->display_name) {
            $receptionist->update([
                'display_name' => 'Receptionist',
                'description' => 'Front desk and appointment management'
            ]);
        }
        $receptionist->syncPermissions([
            'view_patients', 'create_patients', 'edit_patients',
            'view_appointments', 'create_appointments', 'edit_appointments', 'reschedule_appointments', 'manage_waitlist',
            'send_reminders', 'send_notifications'
        ]);

        // Accountant - Financial operations
        $accountant = Role::firstOrCreate(['name' => 'accountant'], [
            'display_name' => 'Accountant',
            'description' => 'Financial management and reporting'
        ]);
        if (!$accountant->display_name) {
            $accountant->update([
                'display_name' => 'Accountant',
                'description' => 'Financial management and reporting'
            ]);
        }
        $accountant->syncPermissions([
            'view_invoices', 'create_invoices', 'edit_invoices', 'mark_payments', 'view_reports', 'export_reports',
            'view_patients' // Need to see patient info for invoicing
        ]);

        // Dentist - Clinical operations
        $dentist = Role::firstOrCreate(['name' => 'dentist'], [
            'display_name' => 'Dentist',
            'description' => 'Clinical care and patient management'
        ]);
        if (!$dentist->display_name) {
            $dentist->update([
                'display_name' => 'Dentist',
                'description' => 'Clinical care and patient management'
            ]);
        }
        $dentist->syncPermissions([
            'view_patients', 'edit_patients',
            'view_appointments', 'edit_appointments',
            'view_invoices'
        ]);
    }
}