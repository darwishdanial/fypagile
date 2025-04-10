<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // User::factory(10)->create();
        // Supervisor::factory(10)->create();
        // Student::factory(10)->create();

        // $this->call(StudentSeeder::class);
        // $this->call(UserSeeder::class);

        $this->call([
            
            StudentPSM1SeederNew::class,
            StudentPSM2SeederNew::class,
            PermissionSeeder::class,
            UserSeederNew::class,
            SupervisorSeeder::class,
            ProjectAreaMappingSeeder::class,
            PanelHistorySeeder::class,
        ]);

    }
}
