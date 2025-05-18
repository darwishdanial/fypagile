<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentPSM1;
use Faker\Factory as Faker;

class StudentPSM1SeederTemp extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ms_MY');

        $projectAreas = [
            'Artificial Intelligence',
            'Cybersecurity',
            'Internet of Things',
            'Software Engineering',
            'Data Science',
            'Computer Vision',
            'Robotics',
            'Human Computer Interaction'
        ];

        $projectTypes = ['System Development', 'Research Based'];

        for ($i = 0; $i < 10; $i++) {
            $name = $faker->name;
            $email = strtolower(str_replace(' ', '.', $name)) . '@graduate.utm.my';
            $matric = $faker->unique()->bothify('A##EC####');
            $projectArea = $faker->randomElement($projectAreas);
            $projectType = $faker->randomElement($projectTypes);

            StudentPSM1::create([
                'course' => 'SECJ',
                'matric' => $matric,
                'name' => $name,
                'title' => $faker->sentence(6),
                'project_area' => $projectArea,
                'project_area_ai' => $projectArea, // or leave null if AI category classification isn't needed
                'project_type' => $projectType,
                'email' => $email,
                'phone' => $faker->phoneNumber,
                'cohort' => '2019/2020',
                'sessionpsm' => '2022/2023',
                'supervisorId' => null,
            ]);
        }
    }
}
