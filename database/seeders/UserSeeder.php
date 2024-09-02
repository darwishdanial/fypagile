<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = [
            
            [
                'name' => 'Dr. Ahmad Safuan Bin Abd Rashid',
                'username' => 'ahmadsafuan',
                'email' => 'safuan@utm.my',
                'role' => '1',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
            [
                'name' => 'Dr. Afikah Binti Rahim',
                'username' => 'afikahrahim',
                'email' => 'afikah@utm.my',
                'role' => '2',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
           
            [
                'name' => 'Dr. Muhammad Farhan Bin Zolkepli',
                'username' => 'farhan',
                'email' => 'farhan@utm.my',
                'role' => '2',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
            [
                'name' => 'Dr. Azman Bin Mohamed',
                'username' => 'azman',
                'email' => 'azman@utm.my',
                'role' => '2',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
            [
                'name' => 'Dr. Haryati Binti Yaacob',
                'username' => 'haryati',
                'email' => 'haryati@utm.my',
                'role' => '2',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
            [
                'name' => 'Dr. Khairul Idham Bin Satar',
                'username' => 'khairulidham',
                'email' => 'khairulidham@utm.my',
                'role' => '2',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
            [
                'name' => 'Dr. Radzuan Bin Saari',
                'username' => 'radzuan',
                'email' => 'radzuan@utm.my',
                'role' => '2',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
            [
                'name' => 'Dr. Abdul Rahman',
                'username' => 'abdulrahman',
                'email' => 'admin@utm.my',
                'role' => '0',
                'isPanel' => '0',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ],
            [
                'name' => 'Dr. Khairun Nissa bt Mat Said',
                'username' => 'khairunnisa',
                'email' => 'khairunnisa@utm.my',
                'role' => '2',
                'isPanel' => '1',
                'email_verified_at' => now(),
                'password' => hash::make(123456),
            ]
            
        ];

        // Insert user data into database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
