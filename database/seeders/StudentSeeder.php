<?php

namespace Database\Seeders;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0094',
            'name' => 'Muhammad Faiz Irfan Bin Ariffin',
            'email' => 'muhammadfaizirfan@graduate.utm.my',
            'phone' => '0123456789',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'psm' => 2,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0090',
            'name' => 'Mohd Idham Bin Anur',
            'email' => 'mohdidham@graduate.utm.my',
            'phone' => '0124567893',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'psm' => 2,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0190',
            'name' => 'Muhammad Firdaus Bin Hishamudin',
            'email' => 'mohdfirdaus@graduate.utm.my',
            'phone' => '0124567894',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'psm' => 2,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0008',
            'name' => 'Ahmad Mujahid Bin Abd Rahmat',
            'email' => 'ahmadmujahid@graduate.utm.my',
            'phone' => '0124567895',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'psm' => 2,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A19EC0153',
            'name' => 'Rakesh a/l Kannapathy',
            'email' => 'rakesh@graduate.utm.my',
            'phone' => '0124567881',
            'cohort' => '2019/2023',
            'sessionpsm' => '2022/2023',
            'psm' => 2,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0079',
            'name' => 'Mohamad Haziq Zikry Bin Mohammad Razak',
            'email' => 'mohamadhaziqzikry@graduate.utm.my',
            'phone' => '0136549879',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'psm' => 1,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0008',
            'name' => 'Aiman Naim Bin Ariffin',
            'email' => 'aimannaim@graduate.utm.my',
            'phone' => '0136549878',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'psm' => 1,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0058',
            'name' => 'Khairul Izzat Bin Hashim',
            'email' => 'khairulizzat@graduate.utm.my',
            'phone' => '0136549868',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'psm' => 1,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0069',
            'name' => 'Aum Jeevan A/L Aum Nirangkar',
            'email' => 'aumjeevan@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'psm' => 1,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0083',
            'name' => 'Muhammad Aniq Aqil Bin Azrai Fahmi',
            'email' => 'muhammadaniqaqil@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'psm' => 1,
            'supervisorId' => null,
        ]);

        Student::create([
            'course' => 'SECJ',
            'matric' => 'A20EC0083',
            'name' => 'Muhammad Albab',
            'email' => 'muhammadalbab@graduate.utm.my',
            'phone' => '0136549545',
            'cohort' => '2020/2024',
            'sessionpsm' => '2023/2024',
            'psm' => 1,
            'supervisorId' => null,
        ]);
        
    }
}
