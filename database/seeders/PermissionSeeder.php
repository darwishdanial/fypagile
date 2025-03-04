<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         // Define permissions
         $permissions = [
            // Dashboard
            'view coordinator dashboard',
            'view panel dashboard',

            // PSM1
            'view psm1 list students table',
            'view psm1 list panels table',
            'view psm1 assign supervisor table',
            'view psm1 assign proposal panel table',
            'view psm1 assign panel table',
            'view psm1 result table',
            'view psm1 evaluation rubric',
            'view psm1 grade supervision table',
            'view psm1 grade proposal table',
            'view psm1 grade table',

            // PSM2
            'view psm2 list students table',
            'view psm2 list panels table',
            'view psm2 assign panel table',
            'view psm2 result table',
            'view psm2 evaluation rubric',
            'view psm2 grade supervision table',
            'view psm2 grade table',
        ];

        // Create and assign permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $coordinatorRole = Role::firstOrCreate(['name' => 'Coordinator']);
        $panelRole = Role::firstOrCreate(['name' => 'Panel']);

        // Assign permissions to Coordinator role
        $coordinatorPermissions = [
            'view coordinator dashboard',

            // PSM1
            'view psm1 list students table',
            'view psm1 list panels table',
            'view psm1 assign supervisor table',
            'view psm1 assign proposal panel table',
            'view psm1 assign panel table',
            'view psm1 result table',
            'view psm1 evaluation rubric',
            'view psm1 grade supervision table',
            'view psm1 grade proposal table',
            'view psm1 grade table',

            // PSM2
            'view psm2 list students table',
            'view psm2 list panels table',
            'view psm2 assign panel table',
            'view psm2 result table',
            'view psm2 evaluation rubric',
            'view psm2 grade supervision table',
            'view psm2 grade table',
        ];
        $coordinatorRole->syncPermissions($coordinatorPermissions);

        // Assign permissions to Panel role
        $panelPermissions = [
            'view panel dashboard',

            // PSM1
            'view psm1 grade supervision table',
            'view psm1 grade proposal table',
            'view psm1 grade table',

            // PSM2
            'view psm2 grade supervision table',
            'view psm2 grade table',
        ];
        $panelRole->syncPermissions($panelPermissions);

    }
}
