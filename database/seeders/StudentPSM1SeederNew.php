<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ProjectLecturerMergerService;
use App\Models\StudentPSM1;
use Faker\Factory as Faker;

class StudentPSM1SeederNew extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {

            $service = new ProjectLecturerMergerService();

            $studentData = $service->fetchStudentData();

            $limitedStudentData = array_slice($studentData, 0, 206);

            $faker = Faker::create('ms_MY');

            foreach ($limitedStudentData as $student) {

                $name = $faker->name;
                $email = strtolower(str_replace(' ', '.', $name)) . '@graduate.utm.my';

                StudentPSM1::create([
                    'course' => 'SECJ',
                    'matric' =>  $faker->unique()->bothify('A##EC####'),
                    'name' => $name, 
                    'title' => $student['project_title'] ?? null,
                    'project_area' => $student['project_area'] ?? null,
                    'project_type' => $student['project_type'] ?? null,
                    'email' => $email,
                    'phone' => $faker->phoneNumber,
                    'cohort' => '2019/2023',
                    'sessionpsm' => '2022/2023',
                    'supervisorId' => null,
                ]);
            }

        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
        }
    }
}
