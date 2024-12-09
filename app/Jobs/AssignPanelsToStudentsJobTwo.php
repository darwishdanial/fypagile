<?php
namespace App\Jobs;

use App\Models\StudentPSM1;
use Phpml\ModelManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ProjectAreaMapping;

class AssignPanelsToStudentsJobTwo implements ShouldQueue
{
    use Dispatchable;

    public function handle()
    {
        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment.model');
        $classifier = $modelManager->restoreFromFile($modelPath);

        $students = StudentPSM1::all();
        $assignedPanels = [];  // Track all assigned panels

        $allPanels = DB::table('users')->pluck('name')->toArray();
        $panelCounts = array_fill_keys($allPanels, 0);  // Track panel usage count

        $totalStudents = $students->count();
        $totalPanels = count($allPanels);

        // Calculate the maximum number of students per panel (use ceiling to round up)
        $maxStudentsPerPanel = ceil($totalStudents / $totalPanels);

        // Assign main panels first
        foreach ($students as $student) {
            // Predict main panel
            $mainPanel = $this->getPanelForStudent($student, $classifier, $allPanels);

            // Make sure the panel is not over-assigned
            if ($panelCounts[$mainPanel] < $maxStudentsPerPanel) {
                // Assign main panel
                $student->update(['panel1id' => $mainPanel]);
                $panelCounts[$mainPanel]++;  // Increment panel count for main panel
                $assignedPanels['main'][] = $mainPanel;
            } else {
                // If panel is full, skip this panel and try another one
                $mainPanel = $this->assignAvailablePanel($panelCounts, $allPanels, $maxStudentsPerPanel);
                $student->update(['panel1id' => $mainPanel]);
                $panelCounts[$mainPanel]++;  // Increment panel count for main panel
                $assignedPanels['main'][] = $mainPanel;
            }
        }

        // Assign secondary panels
        foreach ($students as $student) {
            // Predict secondary panel, ensure it is not the same as the main panel
            $secondaryPanel = $this->getPanelForStudent($student, $classifier, $allPanels);

            // Make sure the panel is not over-assigned
            if ($panelCounts[$secondaryPanel] < $maxStudentsPerPanel) {
                // Assign secondary panel
                $student->update(['panel2id' => $secondaryPanel]);
                $panelCounts[$secondaryPanel]++;  // Increment panel count for secondary panel
                $assignedPanels['secondary'][] = $secondaryPanel;
            } else {
                // If panel is full, skip this panel and try another one
                $secondaryPanel = $this->assignAvailablePanel($panelCounts, $allPanels, $maxStudentsPerPanel, $student->main_panel);
                $student->update(['panel2id' => $secondaryPanel]);
                $panelCounts[$secondaryPanel]++;  // Increment panel count for secondary panel
                $assignedPanels['secondary'][] = $secondaryPanel;
            }
        }
    }

    private function getPanelForStudent($student, $classifier, $allPanels , $excludePanel = null)
    {
        // Prepare features (map the project area and project type)
        $features = [
            $this->getAreaNumericValue($student->project_area),
            $this->getTypeNumericValue($student->project_type),
        ];

        // Predict the panel
        $predictedPanel = $classifier->predict($features);

        // Ensure the panel is not the excluded one (if given)
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
        // Map project type to numeric value
        if ($projectType == 'System Development') {
            return 0;  // System Development => 0
        } elseif ($projectType == 'Research Based') {
            return 1;  // Research Based => 1
        }
        return -1;  // Default to -1 if not matching
    }

    private function assignAvailablePanel($panelCounts, $allPanels, $maxStudentsPerPanel, $excludePanel = null)
    {
        // Assign first available panel in the array //
        // try change here //
        foreach ($allPanels as $panel) {
            if ($panelCounts[$panel] < $maxStudentsPerPanel && $panel != $excludePanel) {
                return $panel;
            }
        }

        return $allPanels[array_rand($allPanels)];
    }

    private function getAlternativePanel($excludedPanel, $allPanels)
    {
        // Find an alternative panel (avoid the excluded panel)
        $availablePanels = array_diff($allPanels, [$excludedPanel]);
        return $availablePanels[array_rand($availablePanels)];
    }
}
