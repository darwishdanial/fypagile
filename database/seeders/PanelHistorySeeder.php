<?php

namespace Database\Seeders;

use App\Models\PanelHistory;  
use App\Models\User;  
use App\Services\ProjectLecturerMergerService;
use Illuminate\Database\Seeder;

class PanelHistorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = new ProjectLecturerMergerService();

        $mappingData = $service->mergePanelAndProjectData();

        foreach ($mappingData as $data) {
            $lecturerId = User::where('name', $data['lecturer_name'])->value('id');

            if ($lecturerId) {
                PanelHistory::create([
                    'panel_id' => $lecturerId,
                    'project_area' => $data['project_area'],
                    'project_type' => $data['project_type'],
                ]);
            }
        }

        $this->command->info('Panel History seeded successfully!');
    }
}
