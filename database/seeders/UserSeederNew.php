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

        $mappingData = $service->mergePanelAndProjectDataWithMapping();

        $lecturerMapping = $mappingData['lecturerMapping'];


        foreach ($lecturerMapping as $name => $number) {

            $role = ($number === 0) ? 1 : 2;
            $isPanel = 1;
            $email = strtolower(str_replace(' ', '.', $name)) . '@utm.my'; // Generate email based on the name

            $user = User::create([
                'name' => $name,
                'username' => strtolower(str_replace(' ', '', $name)),
                'email' => $email,
                'role' => $role,
                'isPanel' => $isPanel,
                'email_verified_at' => now(),
                'password' => Hash::make(123456),
            ]);

            if($role === 1) {
                $user->assignRole('Coordinator');
            } else {
                $user->assignRole('Panel');
            }

        }
    }
}
