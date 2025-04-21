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

            $limitedStudentData = array_slice($studentData, 0, 217);

            $faker = Faker::create('ms_MY');

            foreach ($limitedStudentData as $student) {

                if (empty($student['project_title']) || empty($student['project_area']) || empty($student['project_type'])) {
                    continue;
                }

                $name = $faker->name;
                $email = strtolower(str_replace(' ', '.', $name)) . '@graduate.utm.my';
                $category = $service->matchCategory($student['project_area']);

                StudentPSM1::create([
                    'course' => 'SECJ',
                    'matric' =>  $faker->unique()->bothify('A##EC####'),
                    'name' => $name, 
                    'title' => $student['project_title'],
                    'project_area' => $student['project_area'],
                    'project_area_ai' => $category,
                    'project_type' => $student['project_type'],
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
