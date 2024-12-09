<?php
namespace App\Jobs;

use App\Models\StudentPSM1;
use App\Models\ProjectAreaMapping;
use Phpml\ModelManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignPanelsToStudentsJob implements ShouldQueue{
    use Dispatchable;

    public function handle(){

        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment.model');
        $classifier = $modelManager->restoreFromFile($modelPath);

        $students = StudentPSM1::all();

        foreach ($students as $student) {
            // Prepare features and predict panel assignment
            $features = [
                $this->getAreaNumericValue($student->project_area),
                $this->getTypeNumericValue($student->project_type),
            ];

            $predictedLecturer = $classifier->predict($features);

            // Make sure sama amount all lect
            $student->update(['lecturer_id' => $predictedLecturer]);
        }
    }

    private function getAreaNumericValue($projectArea)
    {
        $areaMapping = ProjectAreaMapping::where('name', $projectArea)->first();
        
        return $areaMapping ? $areaMapping->number : -1;
    }

    private function getTypeNumericValue($projectType)
    {
        if ($projectType == 'System Development') {
            return 0; // System Development => 0
        } elseif ($projectType == 'Research Based') {
            return 1; // Research Based => 1
        }
        return -1; // Default to -1 if not matching;
    }
}
