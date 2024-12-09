<?php

namespace App\Jobs;

use App\Models\StudentPSM1;
use Phpml\ModelManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ProjectAreaMapping;

class AssignPanelsToStudentsJobFour implements ShouldQueue
{
    use Dispatchable;

    public function handle()
    {
        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment.model');
        $classifier = $modelManager->restoreFromFile($modelPath);

        $students = StudentPSM1::all();

        $allPanels = DB::table('users')->pluck('id')->toArray();
        $totalStudents = $students->count();
        $totalPanels = count($allPanels);
        $maxStudentsPerPanel = ceil($totalStudents / $totalPanels);
        $panelCounts = array_fill_keys($allPanels, 0);


        foreach ($students as $student) {

            $features = [
                $this->getAreaNumericValue($student->project_area),
                $this->getTypeNumericValue($student->project_type),
            ];

            $potentialPanels = $this->assignAvailablePanel(
                $allPanels, 
                $classifier, 
                $features
            );

            $primaryPanel = null;
            $secondaryPanel = null;

            foreach ($potentialPanels as $panel => $score) {
                if ($panelCounts[$panel] < $maxStudentsPerPanel) {
                    if (!$primaryPanel) {
                        // Assign the first panel as primary if it isn't full and still not assigned
                        $primaryPanel = $panel;
                        $panelCounts[$panel]++;  // Increment the count for the primary panel
                        $student->update(['main_panel' => $primaryPanel]); 
                    } elseif (!$secondaryPanel) {
                        // Assign the second panel as secondary if it isn't full and still not assigned
                        $secondaryPanel = $panel;
                        $panelCounts[$panel]++;  // Increment the count for the secondary panel
                        $student->update(['secondary_panel' => $secondaryPanel]); 
                    }

                    // Stop if both primary and secondary panels are assigned
                    if ($primaryPanel && $secondaryPanel) {
                        break;
                    }
                }
            }
        }
    }

    private function assignAvailablePanel($allPanels, $classifier, $features)
    {
        $potentialPanels = [];

        foreach ($allPanels as $panel) {
            $featuresWithPanel = array_merge($features, [$panel]); // Add the panel as a feature for prediction
            $score = $classifier->predictProbability($featuresWithPanel); // Get the prediction score
            $potentialPanels[$panel] = $score; // Store the score for each panel
        }

        arsort($potentialPanels);

        return $potentialPanels;
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
        return -1; // Default to -1 if not matching
    }
}
