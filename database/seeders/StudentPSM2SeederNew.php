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

            $limitedStudentData = array_slice($studentData, 216, 216);

            $faker = Faker::create('ms_MY');

            foreach ($limitedStudentData as $student) {

                if (empty($student['title']) || empty($student['area']) || empty($student['type'])) {
                    continue;
                }

                $name = $faker->name;
                $email = strtolower(str_replace(' ', '.', $name)) . '@graduate.utm.my';
                $category = $service->matchCategory($student['area']);

                StudentPSM2::create([
                    'course' => 'SECJ',
                    'matric' =>  $faker->unique()->bothify('A##EC####'),
                    'name' => $name, 
                    'title' => $student['title'],
                    'project_area' => $student['area'],
                    'project_area_ai' => $category,
                    'project_type' => $student['type'],
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
