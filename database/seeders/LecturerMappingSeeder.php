<?php

namespace Database\Seeders;

use App\Models\LecturerMapping;  
use App\Services\ProjectLecturerMergerService;
use Illuminate\Database\Seeder;

class LecturerMappingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = new ProjectLecturerMergerService();

        $mappingData = $service->mergePanelAndProjectDataWithMapping(true);

        $lecturerMapping = $mappingData['lecturerMapping'];

        foreach ($lecturerMapping as $name => $number) {
            LecturerMapping::create([
                'name' => $name,
                'number' => $number,
            ]);
        }

        $this->command->info('Lecturer Mappings seeded successfully!');
    }
}
