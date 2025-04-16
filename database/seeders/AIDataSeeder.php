<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Services\ProjectLecturerMergerService; // Replace with your actual service class name

class AiDataSeeder extends Seeder
{
    protected $service;

    public function __construct(ProjectLecturerMergerService $service)
    {
        $this->service = $service;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get data from your service method
        $data = $this->service->mergePanelAndProjectDataWithMapping();
        
        $samples = $data['samples'];
        $labels = $data['labels'];
        
        // Ensure samples and labels arrays have the same length
        if (count($samples) !== count($labels)) {
            throw new \Exception('Samples and labels arrays must have the same length');
        }
        
        $now = now();
        
        $seedData = [];
        foreach ($samples as $index => $sample) {
            $seedData[] = [
                'project_area' => $sample[0],
                'project_type' => $sample[1],
                'panel_name' => $labels[$index],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        
        // Insert data in chunks to avoid memory issues with large datasets
        foreach (array_chunk($seedData, 100) as $chunk) {
            DB::table('ai_data')->insert($chunk);
        }
        
        $this->command->info('AI data seeded successfully: ' . count($seedData) . ' records created');
    }
}