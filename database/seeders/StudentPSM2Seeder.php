<?php

namespace Database\Seeders;
use App\Models\StudentPSM2;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentPSM2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0079',
            'name' => 'Mohamad Haziq Zikry Bin Mohammad Razak',
            'title' => 'Direct Entry Management System',
            'email' => 'mohamadhaziqzikry@graduate.utm.my',
            'phone' => '0136549879',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0008',
            'name' => 'Aiman Naim Bin Ariffin',
            'title' => 'E-Freelance Marketplace',
            'email' => 'aimannaim@graduate.utm.my',
            'phone' => '0136549878',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0058',
            'name' => 'Khairul Izzat Bin Hashim',
            'title' => 'Angsana: Mobile Application for Angsana Johor Bahru Mall',
            'email' => 'khairulizzat@graduate.utm.my',
            'phone' => '0136549868',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0069',
            'name' => 'Aum Jeevan A/L Aum Nirangkar',
            'title' => 'Direct Entry Management System',
            'email' => 'aumjeevan@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0083',
            'name' => 'Muhammad Aniq Aqil Bin Azrai Fahmi',
            'title' => 'Futsal Court Booking System (Futbook)',
            'email' => 'muhammadaniqaqil@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0280',
            'name' => 'Ain Syakirah Binti Abd Rahman',
            'title' => 'Auto Leveling in Water Foundation',
            'email' => 'ainsyakirah@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0034',
            'name' => 'Irdina Nadia Binti Roslan',
            'title' => 'House Rental Management System',
            'email' => 'irdinanadia@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0077',
            'name' => 'Ahmad Safwan Bin Rosnan Nahar',
            'title' => 'Badminton Court Booking System',
            'email' => 'ahmadsafwan@graduate.utm.my',
            'phone' => '0179934120',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0183',
            'name' => 'Nor Nadia Khadijah Binti Mahdzir',
            'title' => 'Sports Facilities Booking System',
            'email' => 'nadiakhadijah@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);

        StudentPSM2::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0183',
            'name' => 'Khairul Naim Bin Roslan',
            'title' => 'Hasta Car Rental Management System',
            'email' => 'khairulnaim@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'supervisorId' => null,
        ]);
    }
}
