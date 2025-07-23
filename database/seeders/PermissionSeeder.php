<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            //Dashboard
            ['name' => 'dashboard-view', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'dashboard-total-order-cards', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'dashboard-monthly-order-cards', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'dashboard-revenue-cards', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'dashboard-graph', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'dashboard-latest-orders', 'group_name' => 'dashboard', 'guard_name' => 'web'],
            ['name' => 'dashboard-latest-requested-orders', 'group_name' => 'dashboard', 'guard_name' => 'web'],

            // Role Management
            ['name' => 'role-list', 'group_name' => 'role', 'guard_name' => 'web'],
            ['name' => 'role-add', 'group_name' => 'role', 'guard_name' => 'web'],
            ['name' => 'role-edit', 'group_name' => 'role', 'guard_name' => 'web'],
            ['name' => 'role-delete', 'group_name' => 'role', 'guard_name' => 'web'],
            
            // customer Management
            ['name' => 'customer-list', 'group_name' => 'customer', 'guard_name' => 'web'],
            ['name' => 'customer-add', 'group_name' => 'customer', 'guard_name' => 'web'],
            ['name' => 'customer-edit', 'group_name' => 'customer', 'guard_name' => 'web'],
            ['name' => 'customer-delete', 'group_name' => 'customer', 'guard_name' => 'web'],
            ['name' => 'customer-view', 'group_name' => 'customer', 'guard_name' => 'web'],

            // driver Management
            ['name' => 'driver-list', 'group_name' => 'driver', 'guard_name' => 'web'],
            ['name' => 'driver-add', 'group_name' => 'driver', 'guard_name' => 'web'],
            ['name' => 'driver-edit', 'group_name' => 'driver', 'guard_name' => 'web'],
            ['name' => 'driver-delete', 'group_name' => 'driver', 'guard_name' => 'web'],
            ['name' => 'driver-view', 'group_name' => 'driver', 'guard_name' => 'web'],

            // staff Management
            ['name' => 'staff-list', 'group_name' => 'staff', 'guard_name' => 'web'],
            ['name' => 'staff-add', 'group_name' => 'staff', 'guard_name' => 'web'],
            ['name' => 'staff-edit', 'group_name' => 'staff', 'guard_name' => 'web'],
            ['name' => 'staff-delete', 'group_name' => 'staff', 'guard_name' => 'web'],
            ['name' => 'staff-view', 'group_name' => 'staff', 'guard_name' => 'web'],

            // Trashed user Management
            ['name' => 'trashed-user-list', 'group_name' => 'trashed', 'guard_name' => 'web'],
            ['name' => 'trashed-user-restore', 'group_name' => 'trashed', 'guard_name' => 'web'],

            // vehicle Management
            ['name' => 'vehicle-list', 'group_name' => 'vehicle', 'guard_name' => 'web'],
            ['name' => 'vehicle-add', 'group_name' => 'vehicle', 'guard_name' => 'web'],
            ['name' => 'vehicle-edit', 'group_name' => 'vehicle', 'guard_name' => 'web'],
            ['name' => 'vehicle-delete', 'group_name' => 'vehicle', 'guard_name' => 'web'],

            // Product service Management
            ['name' => 'service-list', 'group_name' => 'service', 'guard_name' => 'web'],
            ['name' => 'service-add', 'group_name' => 'service', 'guard_name' => 'web'],
            ['name' => 'service-edit', 'group_name' => 'service', 'guard_name' => 'web'],
            ['name' => 'service-delete', 'group_name' => 'service', 'guard_name' => 'web'],
            ['name' => 'service-variant-add', 'group_name' => 'service', 'guard_name' => 'web'],

            // variant Management
            ['name' => 'variant-list', 'group_name' => 'variant', 'guard_name' => 'web'],
            ['name' => 'variant-add', 'group_name' => 'variant', 'guard_name' => 'web'],
            ['name' => 'variant-edit', 'group_name' => 'variant', 'guard_name' => 'web'],
            ['name' => 'variant-delete', 'group_name' => 'variant', 'guard_name' => 'web'],

            // In store order Management
            ['name' => 'InStore-order-list', 'group_name' => 'InStore', 'guard_name' => 'web'],
            ['name' => 'InStore-order-add', 'group_name' => 'InStore', 'guard_name' => 'web'],
            ['name' => 'InStore-order-edit', 'group_name' => 'InStore', 'guard_name' => 'web'],
            ['name' => 'InStore-order-view', 'group_name' => 'InStore', 'guard_name' => 'web'],

            // order Management
            ['name' => 'order-list', 'group_name' => 'order', 'guard_name' => 'web'],
            ['name' => 'order-add', 'group_name' => 'order', 'guard_name' => 'web'],
            ['name' => 'order-edit', 'group_name' => 'order', 'guard_name' => 'web'],
            ['name' => 'order-view', 'group_name' => 'order', 'guard_name' => 'web'],
            ['name' => 'order-invoice-download', 'group_name' => 'order', 'guard_name' => 'web'],
            ['name' => 'order-requested-list', 'group_name' => 'order', 'guard_name' => 'web'],
            ['name' => 'order-cancelled-list', 'group_name' => 'order', 'guard_name' => 'web'],

            // Promotion Management
            ['name' => 'promotion-list', 'group_name' => 'promotion', 'guard_name' => 'web'],
            ['name' => 'promotion-add', 'group_name' => 'promotion', 'guard_name' => 'web'],
            ['name' => 'promotion-edit', 'group_name' => 'promotion', 'guard_name' => 'web'],
            ['name' => 'promotion-delete', 'group_name' => 'promotion', 'guard_name' => 'web'],
            ['name' => 'promotion-view', 'group_name' => 'promotion', 'guard_name' => 'web'],

            // Point Management
            ['name' => 'point-list', 'group_name' => 'point', 'guard_name' => 'web'],
            ['name' => 'point-add', 'group_name' => 'point', 'guard_name' => 'web'],
            ['name' => 'point-edit', 'group_name' => 'point', 'guard_name' => 'web'],
            ['name' => 'point-delete', 'group_name' => 'point', 'guard_name' => 'web'],
            ['name' => 'point-view', 'group_name' => 'point', 'guard_name' => 'web'],


            // Customer Address Management
            ['name' => 'customerAddress-list', 'group_name' => 'customerAddress', 'guard_name' => 'web'],
            ['name' => 'customerAddress-add', 'group_name' => 'customerAddress', 'guard_name' => 'web'],
            ['name' => 'customerAddress-edit', 'group_name' => 'customerAddress', 'guard_name' => 'web'],
            ['name' => 'customerAddress-delete', 'group_name' => 'customerAddress', 'guard_name' => 'web'],
            ['name' => 'customerAddress-view', 'group_name' => 'customerAddress', 'guard_name' => 'web'],

            // Wallet Management
            ['name' => 'promotion-list', 'group_name' => 'promotion', 'guard_name' => 'web'],
            ['name' => 'promotion-view', 'group_name' => 'promotion', 'guard_name' => 'web'],

            // Content Page Management
            ['name' => 'contentPage-about-us', 'group_name' => 'contentPage', 'guard_name' => 'web'],
            ['name' => 'contentPage-privacy-and-policy', 'group_name' => 'contentPage', 'guard_name' => 'web'],
            ['name' => 'contentPage-terms-and-conditions', 'group_name' => 'contentPage', 'guard_name' => 'web'],
            ['name' => 'contentPage-FAQ', 'group_name' => 'contentPage', 'guard_name' => 'web'],

            // notification Management
            ['name' => 'notification-list', 'group_name' => 'notification', 'guard_name' => 'web'],
            ['name' => 'notification-delete', 'group_name' => 'notification', 'guard_name' => 'web'],

            // tax Management
            ['name' => 'tax-list', 'group_name' => 'tax', 'guard_name' => 'web'],
            ['name' => 'tax-add', 'group_name' => 'tax', 'guard_name' => 'web'],
            ['name' => 'tax-edit', 'group_name' => 'tax', 'guard_name' => 'web'],
            ['name' => 'tax-delete', 'group_name' => 'tax', 'guard_name' => 'web'],

            // config setting Management
            ['name' => 'config-smtp', 'group_name' => 'config', 'guard_name' => 'web'],
            ['name' => 'config-stripe', 'group_name' => 'config', 'guard_name' => 'web'],
            ['name' => 'config-delivery', 'group_name' => 'config', 'guard_name' => 'web'],
            ['name' => 'config-time-shedule', 'group_name' => 'config', 'guard_name' => 'web'],
            ['name' => 'config-general-setting', 'group_name' => 'config', 'guard_name' => 'web'],

            // Help desk Management
            ['name' => 'helpdesk-list', 'group_name' => 'helpdesk', 'guard_name' => 'web'],
            ['name' => 'helpdesk-reply', 'group_name' => 'helpdesk', 'guard_name' => 'web'],

            // Transaction Management
            ['name' => 'transaction-list', 'group_name' => 'transaction', 'guard_name' => 'web'],

            // Income Report Management
            ['name' => 'income-report', 'group_name' => 'income', 'guard_name' => 'web'],
            ['name' => 'income-export', 'group_name' => 'income', 'guard_name' => 'web'],

            // Wallet Management
            ['name' => 'wallet-list', 'group_name' => 'wallet', 'guard_name' => 'web'],
           
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate($permission);
        }

       $role =  Role::firstWhere('name',config('constants.ROLES.ADMIN'));
       $admin_permissions = Permission::pluck('name')->toArray();
       $user = User::firstWhere('role_id',$role->id);
       $user->syncPermissions($admin_permissions);
    }
}
