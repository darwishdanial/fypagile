<?php

namespace App\Jobs;

use App\Models\StudentPSM1;
use Phpml\ModelManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ProjectAreaMapping;

class AssignPanelsToStudentsJobThree implements ShouldQueue
{
    use Dispatchable;

    public function handle()
    {
        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment.model');
        $classifier = $modelManager->restoreFromFile($modelPath);

        $students = StudentPSM1::all();
        $assignedPanels = [];
        
        // Get all possible panels from the system
        $allPanels = DB::table('project_area_mappings')->pluck('name')->toArray();
        $totalStudents = count($students);
        $totalPanels = count($allPanels);
        $maxStudentsPerPanel = ceil($totalStudents / $totalPanels);

        $panelCounts = array_fill_keys($allPanels, 0); // Track panel usage count

        // Assign main and secondary panels
        foreach ($students as $student) {
            $features = [
                $this->getAreaNumericValue($student->project_area),
                $this->getTypeNumericValue($student->project_type),
            ];

            // Assign main panel
            $mainPanel = $this->getPanelForStudent($student, $classifier, null, $allPanels);
            if ($panelCounts[$mainPanel] >= $maxStudentsPerPanel) {
                $mainPanel = $this->assignAvailablePanel($panelCounts, $allPanels, $maxStudentsPerPanel, null, $classifier, $features);
            }
            $student->update(['main_panel' => $mainPanel]);
            $panelCounts[$mainPanel]++;
            $assignedPanels['main'][] = $mainPanel;

            // Assign secondary panel
            $secondaryPanel = $this->getPanelForStudent($student, $classifier, $mainPanel, $allPanels);
            if ($panelCounts[$secondaryPanel] >= $maxStudentsPerPanel) {
                $secondaryPanel = $this->assignAvailablePanel($panelCounts, $allPanels, $maxStudentsPerPanel, $mainPanel, $classifier, $features);
            }
            $student->update(['secondary_panel' => $secondaryPanel]);
            $panelCounts[$secondaryPanel]++;
            $assignedPanels['secondary'][] = $secondaryPanel;
        }
    }

    private function getPanelForStudent($student, $classifier, $excludePanel = null, $allPanels)
    {
        $features = [
            $this->getAreaNumericValue($student->project_area),
            $this->getTypeNumericValue($student->project_type),
        ];

        $predictedPanel = $classifier->predict($features);

        if ($excludePanel && $predictedPanel == $excludePanel) {
            return $this->getAlternativePanel($predictedPanel, $allPanels);
        }

        return $predictedPanel;
    }

    private function getAreaNumericValue($projectArea)
    {
        $areaMapping = ProjectAreaMapping::where('name', $projectArea)->first();

        return $areaMapping ? $areaMapping->number : -1;
    }

    private function getTypeNumericValue($projectType)
    {
        if ($projectType == 'System Development') {
            return 0;
        } elseif ($projectType == 'Research Based') {
            return 1;
        }
        return -1;
    }

    private function assignAvailablePanel($panelCounts, $allPanels, $maxStudentsPerPanel, $excludePanel = null, $classifier, $features)
    {
        $potentialPanels = [];

        //return probability of each panel for the student
        foreach ($allPanels as $panel) {
            if ($panelCounts[$panel] < $maxStudentsPerPanel && $panel != $excludePanel) {  //skip $excludePanel
                $featuresWithPanel = array_merge($features, [$panel]);
                $score = $classifier->predictProbability($featuresWithPanel);
                $potentialPanels[$panel] = $score;
            }
        }

        //get the highest probability
        if (!empty($potentialPanels)) {
            arsort($potentialPanels);
            return array_key_first($potentialPanels);
        }

        return $allPanels[array_rand($allPanels)];
    }

    private function getAlternativePanel($excludedPanel, $allPanels)
    {
        $availablePanels = array_diff($allPanels, [$excludedPanel]);
        return $availablePanels[array_rand($availablePanels)];
    }
}
