<?php

namespace Database\Seeders;
use App\Models\StudentPSM1;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentPSM1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0095',
            'name' => 'Azrulhakim Bin Zamri',
            'title' => 'Homebaker Inventory Management System',
            'email' => 'azrulhakim@graduate.utm.my',
            'phone' => '0123456789',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0097',
            'name' => 'Muhammad Adam bin Jamal',
            'title' => 'Housemate Management Mobile Application',
            'email' => 'adamjamal@graduate.utm.my',
            'phone' => '0123456789',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0099',
            'name' => 'Muhammad Zafri Bin Saiful',
            'title' => 'House Rental Management System',
            'email' => 'zafrisaiful@graduate.utm.my',
            'phone' => '0123456789',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0194',
            'name' => 'Ameer Ikhwan Bin Jazlan',
            'title' => 'Food Ordering System in Extended Reality Application',
            'email' => 'ameerikhwan@graduate.utm.my',
            'phone' => '0123456789',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0197',
            'name' => 'Ruhaizad Bin Ramli',
            'title' => 'Charity System Based on Blockchain',
            'email' => 'sitiaisyah@graduate.utm.my',
            'phone' => '0123456789',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0094',
            'name' => 'Muhammad Adam Bin Azman',
            'title' => 'Centralized UTM Club and Society System',
            'email' => 'muhammadfaizirfan@graduate.utm.my',
            'phone' => '0123456789',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0090',
            'name' => 'Haikal Bin Shariman',
            'title' => 'CarStar-A Garage Finder Mobile Application',
            'email' => 'haikalshariman@graduate.utm.my',
            'phone' => '0124567893',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0190',
            'name' => 'Muhammad Firdaus Bin Hishamudin',
            'title' => 'EKB Online Ordering and Management System',
            'email' => 'mohdfirdaus@graduate.utm.my',
            'phone' => '0124567894',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0008',
            'name' => 'Ahmad Mujahid Bin Abd Rahmat',
            'title' => 'Employee Healthcare: Benefits Application System',
            'email' => 'ahmadmujahid@graduate.utm.my',
            'phone' => '0124567895',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

        StudentPSM1::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0153',
            'name' => 'Muhammad Hafizudin Bin Khairudin',
            'title' => 'Direct Entry Management System',
            'email' => 'hafizudin@graduate.utm.my',
            'phone' => '0124567881',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'supervisorId' => null,
        ]);

    }
}
