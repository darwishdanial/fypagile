<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeederTemp extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        static $matricCounter = 1;

        // Create 10 users
        for ($i = 0; $i < 10; $i++) {

            $name = $faker->name;
            $email = strtolower(str_replace(' ', '.', $name)) . '@utm.my';
            $username = strtolower(str_replace(' ', '', $name));
            $matric = sprintf("B21EC%03d", $matricCounter);
            $role = $i === 0 ? 1 : 2; // First user is Coordinator, others are Panel

            $user = User::create([
                'matricNo' => $matric,
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'isSupervisorPSM1' => 1,
                'isPanelPSM1' => 1,
                'isSupervisorPSM2' => 1,
                'isPanelPSM2' => 1,
                'isArchivePSM1' => 0,
                'isArchivePSM2' => 0,
                'email_verified_at' => now(),
                'password' => Hash::make(123456),
            ]);

            if ($role === 1) {
                $user->assignRole('Coordinator');
            } else {
                $user->assignRole('Panel');
            }

            $matricCounter++;
        }
    }
}
