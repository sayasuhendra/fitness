<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminRoleSeeder extends Seeder
{
    public const ADMIN_ROLES = [
        'Owner',
        'Super admin',
        'Admin di lokasi',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->seedPermissions();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::ADMIN_ROLES as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        $allPermissions = Permission::query()
            ->where('guard_name', 'web')
            ->pluck('name')
            ->all();

        foreach (['Owner', 'Super admin'] as $roleName) {
            Role::findByName($roleName, 'web')->syncPermissions($allPermissions);
        }

        Role::findByName('Admin di lokasi', 'web')
            ->syncPermissions($this->locationAdminPermissions($allPermissions));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param  array<int, string>  $allPermissions
     * @return array<int, string>
     */
    private function locationAdminPermissions(array $allPermissions): array
    {
        $allowedPermissions = [
            'ViewAny:Member',
            'View:Member',
            'Create:Member',
            'Update:Member',
            'ViewAny:Trainer',
            'View:Trainer',
            'Create:Trainer',
            'Update:Trainer',
            'ViewAny:FitnessClass',
            'View:FitnessClass',
            'Create:FitnessClass',
            'Update:FitnessClass',
            'ViewAny:MembershipPurchase',
            'View:MembershipPurchase',
            'Create:MembershipPurchase',
            'Update:MembershipPurchase',
            'ViewAny:PaymentConfirmation',
            'View:PaymentConfirmation',
            'Update:PaymentConfirmation',
            'ViewAny:Attendance',
            'View:Attendance',
            'Create:Attendance',
            'Update:Attendance',
            'ViewAny:PersonalTrainerSession',
            'View:PersonalTrainerSession',
            'Create:PersonalTrainerSession',
            'Update:PersonalTrainerSession',
            'ViewAny:Order',
            'View:Order',
            'Create:Order',
            'Update:Order',
            'ViewAny:Product',
            'View:Product',
            'Create:Product',
            'Update:Product',
            'ViewAny:ProductCategory',
            'View:ProductCategory',
            'Create:ProductCategory',
            'Update:ProductCategory',
            'ViewAny:Facility',
            'View:Facility',
            'Create:Facility',
            'Update:Facility',
        ];

        return array_values(array_filter(
            $allPermissions,
            fn (string $permission): bool => in_array($permission, $allowedPermissions, true)
        ));
    }

    private function seedPermissions(): void
    {
        $models = [
            'Attendance',
            'BankAccount',
            'Facility',
            'FitnessClass',
            'Member',
            'MembershipPackage',
            'MembershipPurchase',
            'Order',
            'PaymentConfirmation',
            'PersonalTrainerSession',
            'Product',
            'ProductCategory',
            'QrisPaymentMethod',
            'Role',
            'Trainer',
            'User',
        ];

        $abilities = [
            'ViewAny',
            'View',
            'Create',
            'Update',
            'Delete',
            'DeleteAny',
            'Restore',
            'RestoreAny',
            'ForceDelete',
            'ForceDeleteAny',
            'Replicate',
            'Reorder',
        ];

        foreach ($models as $model) {
            foreach ($abilities as $ability) {
                Permission::findOrCreate("{$ability}:{$model}", 'web');
            }
        }
    }
}
