<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Services\ProjectLecturerMergerService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class UserSeederNew extends Seeder
{

    public function run()
    {

        $service = new ProjectLecturerMergerService();

        $mappingData = $service->mergePanelAndProjectDataWithMapping(false);

        $lecturerMapping = $mappingData['lecturerMapping'];

        // dd($lecturerMapping);


        foreach ($lecturerMapping as $name => $number) {

            $role = ($number === 0) ? 1 : 2;
            $email = strtolower(str_replace(' ', '.', $name)) . '@utm.my'; // Generate email based on the name
            static $matricCounter = 1; // Start from 1
            $matric = sprintf("B21EC%03d", $matricCounter); // Format as B21EC001, B21EC002, ...

            $user = User::create([
                'matricNo' => $matric,
                'name' => $name,
                'username' => strtolower(str_replace(' ', '', $name)),
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

            if($role === 1) {
                $user->assignRole('Coordinator');
            } else {
                $user->assignRole('Panel');
            }

            $matricCounter++;

        }
    }
}
