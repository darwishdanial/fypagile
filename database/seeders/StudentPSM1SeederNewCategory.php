<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ProjectLecturerMergerService;
use App\Models\StudentPSM1;
use Faker\Factory as Faker;

class StudentPSM1SeederNewCategory extends Seeder
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
            $faker = Faker::create('ms_MY');
            
            // Get categories
            // $categories = $this->getCategories();
            // $categories = $service->getCategories();
            
            // Group students by category
            // $groupedStudents = $this->groupStudentsByCategory($studentData, $categories);

            // Define how many students to create per category
            $studentsPerCategoryLimit = [
                'Mobile Application' => 10, //+2 -
                'Web Development' => 15, //+2 -
                'Machine Learning' => 25, //+2 
                'Security' => 15, //+2 
                'Augmented Reality' => 15, //+2 
                'Game Development' => 14, //+2 
                'Management' => 14, //+1 
                'Education' => 13, // 
                'Networking' => 13, // 
                'Data Science & Analytics' => 9, //9 
                'Health & Medical' => 13, // 
                'Financial & Business' => 13,// 
                'Human-Computer Interaction (HCI)' => 12,  // 
                'Computer Vision' => 13, // -
                'Social & Tourism' => 9, //9 
                // 'Multimedia' => 12,
                'UTM' => 6, //6 
                'Others' => 7 // 
            ];

            $machineLearningCount = 0;
            $machineLearningResearh = 3; 

            // Group students by category
            $studentsPerCategory = [];
            
            // Keep track of how many students we've created per category
            $studentsPerCategory = [];
            // $maxStudentsPerCategory = 3; // Limit to 3 students per category
            
            foreach ($studentData as $student) {
                // Skip if any of the required fields are missing
                if (empty($student['project_title']) || empty($student['project_area']) || empty($student['project_type'])) {
                    continue;
                }
                
                // Determine the category for this student using ONLY project_area
                // $category = $this->determineCategory($student['project_area'], $categories);
                $category = $service->matchCategory($student['project_area']);
                
                // Initialize counter for this category if it doesn't exist
                if (!isset($studentsPerCategory[$category])) {
                    $studentsPerCategory[$category] = 0;
                }
                
                // Skip if we already have 3 students for this category
                if ($studentsPerCategory[$category] >= $studentsPerCategoryLimit[$category]) {
                    continue;
                }
                
                // Create the student
                $name = $faker->name;
                $email = strtolower(str_replace(' ', '.', $name)) . '@graduate.utm.my';
                
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
                    'cohort' => '2019/2023',
                    'sessionpsm' => '2022/2023',
                    'supervisorId' => null,
                ]);
                
                // Increment the count for this category
                $studentsPerCategory[$category]++;
                
                // Output progress
                // $this->command->info("Created student in category: $category ({$studentsPerCategory[$category]}/{$studentsPerCategoryLimit[$category]})");
            }
            
            // Summary
            $this->command->info("Seeding complete. Students created per category:");
            foreach ($studentsPerCategory as $category => $count) {
                $this->command->info("- $category: $count students");
            }

        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
        }
    }

    /**
     * Group students by their project category
     */
    // private function groupStudentsByCategory(array $students, array $categories): array
    // {
    //     $grouped = [];
        
    //     foreach ($students as $student) {
    //         if (empty($student['project_title']) || empty($student['project_area']) || empty($student['project_type'])) {
    //             continue;
    //         }
            
    //         $category = $this->determineCategory($student['project_area'], $categories);
            
    //         if (!isset($grouped[$category])) {
    //             $grouped[$category] = [];
    //         }
            
    //         $grouped[$category][] = $student;
    //     }
        
    //     return $grouped;
    // }


}