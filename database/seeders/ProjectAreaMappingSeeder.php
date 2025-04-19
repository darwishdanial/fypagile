<?php

namespace Database\Seeders;

use App\Models\ProjectAreaMapping;  
use App\Services\ProjectLecturerMergerService;
use Illuminate\Database\Seeder;

class ProjectAreaMappingSeeder extends Seeder
{
    public function run()
    {
        $service = new ProjectLecturerMergerService();

        $mappingData = $service->mergePanelAndProjectDataWithMapping(true);

        $areaMapping = $mappingData['areaMapping'];

        foreach ($areaMapping as $name => $number) {
            ProjectAreaMapping::create([
                'name' => $name,
                'number' => $number,
            ]);
        }

        $this->command->info('Project Area Mappings seeded successfully!');
    }
}

