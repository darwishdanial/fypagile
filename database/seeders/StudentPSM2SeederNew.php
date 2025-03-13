<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ProjectLecturerMergerService;
use App\Models\StudentPSM2;
use Faker\Factory as Faker;

class StudentPSM2SeederNew extends Seeder
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

            $limitedStudentData = array_slice($studentData, 206, 206);

            $faker = Faker::create('ms_MY');

            foreach ($limitedStudentData as $student) {

                $name = $faker->name;
                $email = strtolower(str_replace(' ', '.', $name)) . '@graduate.utm.my';

                StudentPSM2::create([
                    'course' => 'SECJ',
                    'matric' =>  $faker->unique()->bothify('A##EC####'),
                    'name' => $name, 
                    'title' => $student['project_title'] ?? null,
                    'project_area' => $student['project_area'] ?? "AI Development",
                    'project_type' => $student['project_type'] ?? "System Development",
                    'email' => $email,
                    'phone' => $faker->phoneNumber,
                    'cohort' => '2019/2020',
                    'sessionpsm' => '2022/2023',
                    'supervisorId' => null,
                ]);
            }

        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
        }
    }
}
