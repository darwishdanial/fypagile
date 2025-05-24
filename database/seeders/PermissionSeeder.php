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
            'view psm1 result coordinator table',
            'view psm1 research rubric',
            'view psm1 grade supervision table',
            'view psm1 grade proposal table',
            'view psm1 grade table',
            'archive psm1 students',
            'restore psm1 students',
            'delete psm1 students',
            'store psm1 students',
            'update psm1 students',
            'import psm1 students',
            'bulk archive psm1 students',
            'view psm1 result panel table',
            'view psm1 development rubric table',
            'store psm1 evaluation rubric',
            'store psm1 evaluation criteria',
            'update psm1 evaluation rubric',
            'update psm1 evaluation criteria',
            'archive psm1 evaluation rubric',
            'delete psm1 evaluation rubric',
            'restore psm1 evaluation rubric',
            'delete psm1 evaluation criteria',
            'archive psm1 panels',
            'restore psm1 panels',
            'store psm1 panels',
            'update psm1 panels',
            'delete psm1 panels',
            'bulk archive psm1 panels',
            'download panel sample',
            'import panels',
            'view psm1 grade supervision',
            'view psm1 grade panel',
            'view psm1 grade coordinator',
            'store psm1 score',
            'view psm1 supervisor student list',
            'assign psm1 supervisor',
            'unassign psm1 supervisor',
            'view psm1 assign panel table',
            'view psm1 panel student list',
            'assign psm1 panel 1',
            'assign psm1 panel 2',
            'unassign psm1 panel 1',
            'unassign psm1 panel 2',
            'predict psm1 panel',
            'remove all psm1 panel ids',
            'accept psm1 supervisor',
            'request psm1 supervisor',
            'cancel request psm1 supervisor',
            'view psm1 supervisor request',
            'reject psm1 supervisor',
            'delete psm1 score',
            

            // PSM2
            'view psm2 list students table',
            'view psm2 list panels table',
            'view psm2 assign panel table',
            'view psm2 result coordinator table',
            'view psm2 evaluation rubric',
            'view psm2 grade supervision table',
            'view psm2 grade table',
            'archive psm2 students',
            'restore psm2 students',
            'delete psm2 students',
            'store psm2 students',
            'update psm2 students',
            'import psm2 students',
            'bulk archive psm2 students',
            'view project progress psm2 students',
            'view psm2 result panel table',
            'view psm2 development rubric table',
            'view psm2 research rubric',
            'store psm2 evaluation rubric',
            'store psm2 evaluation criteria',
            'update psm2 evaluation rubric',
            'update psm2 evaluation criteria',
            'archive psm2 evaluation rubric',
            'delete psm2 evaluation rubric',
            'restore psm2 evaluation rubric',
            'delete psm2 evaluation criteria',
            'archive psm2 panels',
            'restore psm2 panels',
            'delete psm2 panels',
            'bulk archive psm2 panels',
            'store psm2 panels',
            'update psm2 panels',
            'view psm2 grade supervision',
            'view psm2 grade panel',
            'view psm2 grade coordinator',
            'store psm2 score',
            'view psm2 assign supervisor table',
            'view psm2 supervisor student list',
            'assign psm2 supervisor',
            'unassign psm2 supervisor',
            'view psm2 assign panel table',
            'view psm2 panel student list',
            'assign psm2 panel 1',
            'assign psm2 panel 2',
            'unassign psm2 panel 1',
            'unassign psm2 panel 2',
            'predict psm2 panel',
            'remove all psm2 panel ids',
            'accept psm2 supervisor',
            'request psm2 supervisor',
            'cancel request psm2 supervisor',
            'view psm2 supervisor request',
            'reject psm2 supervisor',
            'delete psm2 score',

            //ML
            'view ml data',
            'view panel history',
            'export ai data',
            'view sample data',
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
            'view psm1 result coordinator table',
            'view psm1 grade supervision table',
            'view psm1 grade proposal table',
            'view psm1 grade table',
            'archive psm1 students',
            'restore psm1 students',
            'delete psm1 students',
            'store psm1 students',
            'update psm1 students',
            'import psm1 students',
            'bulk archive psm1 students',
            'view psm1 development rubric table',
            'view psm1 research rubric',
            'store psm1 evaluation rubric',
            'store psm1 evaluation criteria',
            'update psm1 evaluation rubric',
            'update psm1 evaluation criteria',
            'archive psm1 evaluation rubric',
            'delete psm1 evaluation rubric',
            'restore psm1 evaluation rubric',
            'delete psm1 evaluation criteria',
            'archive psm1 panels',
            'restore psm1 panels',
            'store psm1 panels',
            'update psm1 panels',
            'delete psm1 panels',
            'bulk archive psm1 panels',
            'download panel sample',
            'import panels',
            'view psm1 grade supervision',
            'view psm1 grade panel',
            'view psm1 grade coordinator',
            'store psm1 score',
            'view psm1 supervisor student list',
            'assign psm1 supervisor',
            'unassign psm1 supervisor',
            'view psm1 assign panel table',
            'view psm1 panel student list',
            'assign psm1 panel 1',
            'assign psm1 panel 2',
            'unassign psm1 panel 1',
            'unassign psm1 panel 2',
            'predict psm1 panel',
            'remove all psm1 panel ids',
            'accept psm1 supervisor',
            'view psm1 supervisor request',
            'request psm1 supervisor',
            'cancel request psm1 supervisor',
            'reject psm1 supervisor',
            'delete psm1 score',


            // PSM2
            'view psm2 list students table',
            'view psm2 list panels table',
            'view psm2 assign panel table',
            'view psm2 result coordinator table',
            'view psm2 evaluation rubric',
            'view psm2 grade supervision table',
            'view psm2 grade table',
            'archive psm2 students',
            'restore psm2 students',
            'delete psm2 students',
            'store psm2 students',
            'update psm2 students',
            'import psm2 students',
            'bulk archive psm2 students',
            'view project progress psm2 students',
            'view psm2 development rubric table',
            'view psm2 research rubric',
            'store psm2 evaluation rubric',
            'store psm2 evaluation criteria',
            'update psm2 evaluation rubric',
            'update psm2 evaluation criteria',
            'archive psm2 evaluation rubric',
            'delete psm2 evaluation rubric',
            'restore psm2 evaluation rubric',
            'delete psm2 evaluation criteria',
            'archive psm2 panels',
            'restore psm2 panels',
            'delete psm2 panels',
            'bulk archive psm2 panels',
            'store psm2 panels',
            'update psm2 panels',
            'view psm2 grade supervision',
            'view psm2 grade panel',
            'view psm2 grade coordinator',
            'store psm2 score',
            'view psm2 assign supervisor table',
            'view psm2 supervisor student list',
            'assign psm2 supervisor',
            'unassign psm2 supervisor',
            'view psm2 assign panel table',
            'view psm2 panel student list',
            'assign psm2 panel 1',
            'assign psm2 panel 2',
            'unassign psm2 panel 1',
            'unassign psm2 panel 2',
            'predict psm2 panel',
            'remove all psm2 panel ids',
            'accept psm2 supervisor',
            'view psm2 supervisor request',
            'request psm2 supervisor',
            'cancel request psm2 supervisor',
            'reject psm2 supervisor',
            'delete psm2 score',

            //ML
            'view ml data',
            'view panel history',
            'export ai data',
            'view sample data',
        ];
        $coordinatorRole->syncPermissions($coordinatorPermissions);

        // Assign permissions to Panel role
        $panelPermissions = [
            'view panel dashboard',

            // PSM1
            'view psm1 grade supervision table',
            'view psm1 grade proposal table',
            'view psm1 grade table',
            'view psm1 result panel table',
            'view psm1 grade supervision',
            'view psm1 grade panel',
            'store psm1 score',
            'accept psm1 supervisor',
            'view psm1 supervisor request',
            'reject psm1 supervisor',
            'delete psm1 score',


            // PSM2
            'view psm2 grade supervision table',
            'view psm2 grade table',
            'view psm2 result panel table',
            'view psm2 grade supervision',
            'view psm2 grade panel',
            'store psm2 score',
            'accept psm2 supervisor',
            'view psm2 supervisor request',
            'view project progress psm2 students',
            'reject psm2 supervisor',
            'delete psm2 score',
        ];
        $panelRole->syncPermissions($panelPermissions);

    }
}
