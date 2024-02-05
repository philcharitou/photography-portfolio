<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'invoice_view']);
        Permission::create(['name' => 'invoice_create']);
        Permission::create(['name' => 'invoice_edit']);
        Permission::create(['name' => 'invoice_delete']);
        Permission::create(['name' => 'invoice_approve']);
        Permission::create(['name' => 'invoice_assign']);

        Permission::create(['name' => 'document_request']);
        Permission::create(['name' => 'document_view']);
        Permission::create(['name' => 'document_create']);
        Permission::create(['name' => 'document_edit']);
        Permission::create(['name' => 'document_delete']);
        Permission::create(['name' => 'document_approve']);
        Permission::create(['name' => 'document_assign']);

        Permission::create(['name' => 'bug_report_view']);
        Permission::create(['name' => 'bug_report_create']);
        Permission::create(['name' => 'bug_report_delete']);

        Permission::create(['name' => 'product_view']);
        Permission::create(['name' => 'product_create']);
        Permission::create(['name' => 'product_edit']);
        Permission::create(['name' => 'product_delete']);

        Permission::create(['name' => 'contact_view']);
        Permission::create(['name' => 'contact_create']);
        Permission::create(['name' => 'contact_edit']);
        Permission::create(['name' => 'contact_delete']);

        Permission::create(['name' => 'email_view']);
        Permission::create(['name' => 'email_edit']);
        Permission::create(['name' => 'email_create']);
        Permission::create(['name' => 'email_delete']);
        Permission::create(['name' => 'email_approve']);
        Permission::create(['name' => 'email_send']);

        Permission::create(['name' => 'note_view']);
        Permission::create(['name' => 'note_edit']);
        Permission::create(['name' => 'note_create']);
        Permission::create(['name' => 'note_delete']);

        // ROLES GO HERE
        $roles_array = [
            'accounting' => Role::create(['name' => 'accounting']),
            'accounting_management' => Role::create(['name' => 'accounting_management']),
            'sales' => Role::create(['name' => 'sales']),
            'sales_management' => Role::create(['name' => 'sales_management']),
            'admin' => Role::create(['name' => 'admin']),
        ];
        // ROLES GO HERE
        $accounting_permissions = [
            'invoice_view',
            'invoice_create',
            'invoice_edit',
            'invoice_delete',
            'invoice_approve',
            'document_request',
            'bug_report_create',
            'product_view',
            'contact_view',
            'contact_create',
            'contact_edit',
              // Notes
            'note_view',
            'note_edit',
            'note_create',
            'note_delete',
        ];
        $accounting_management_permissions = [
            'document_view',
            'document_create',
            'document_edit',
            'document_delete',
            'document_approve',
            'invoice_view',
            'invoice_create',
            'invoice_edit',
            'invoice_delete',
            'invoice_approve',
            'document_request',
            'bug_report_create',
            'product_view',
            // Contact Permissions
            'contact_view',
            'contact_create',
            'contact_edit',
            'contact_delete',
            // End of Contact Permissions
              // Notes
            'note_view',
            'note_edit',
            'note_create',
            'note_delete',
        ];

        $sales_permissions = [
            'product_create',
            'product_edit',
            'invoice_view',
            'document_request',
            'bug_report_create',
            'product_view',
            'contact_view',
            'contact_create',
            'contact_edit',
              // Notes
            'note_view',
            'note_edit',
            'note_create',
            'note_delete',
        ];
        $sales_management_permissions = [
            'product_view',
            'product_create',
            'product_edit',
            'product_delete',
            'contact_delete',
            'invoice_view',
            'document_request',
            'bug_report_create',
            'contact_view',
            'contact_create',
            'contact_edit',
              // Notes
            'note_view',
            'note_edit',
            'note_create',
            'note_delete',
        ];

        $admin_permissions = [
            'product_delete',
            'contact_delete',
            'product_create',
            'product_edit',
            'invoice_view',
            'document_request',
            'bug_report_create',
            'product_view',
            'contact_view',
            'contact_create',
            'contact_edit',
              // Notes
            'note_view',
            'note_edit',
            'note_create',
            'note_delete',
        ];

        foreach($roles_array as $role => $role_object) {
            foreach(${ $role . '_permissions'} as $permission) {
                $role_object->givePermissionTo($permission);
            }
        }

        Role::create(['name' => 'super_admin']);
    }
}
