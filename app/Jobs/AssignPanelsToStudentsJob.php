<?php

namespace App\Jobs;

use App\Models\StudentPSM1;
use Phpml\ModelManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ProjectAreaMapping;

class AssignPanelsToStudentsJob implements ShouldQueue
{
    use Dispatchable;

    public function handle()
    {
        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment_svc.model');
        $classifier = $modelManager->restoreFromFile($modelPath);
        $students = StudentPSM1::all();


        // $allPanels = DB::table('users')->pluck('id')->toArray();
        // $allPanels = array_map(function($panelId) {
        //     return $panelId - 1; // Subtract 1 from each panel ID to start from 0
        // }, $allPanels);
        $allPanels = DB::table('lecturer_mapping')->pluck('number')->toArray();
        $totalStudents = $students->count();
        $totalPanels = count($allPanels);
        $maxStudentsPerPanel = ceil($totalStudents / $totalPanels) * 2;
        $panelCounts = array_fill_keys($allPanels, 0);

        foreach ($students as $student) {

            logger("Student id: {$student->id}");

            $features = [
                $this->getAreaNumericValue($student->project_area), 
                $this->getTypeNumericValue($student->project_type),
            ];

            logger('Student Features: ' . json_encode($features));

            $potentialPanels = $this->assignAvailablePanel(
                $classifier,
                $features
            );

            arsort($potentialPanels);
        
            //logger('Potential Panels with Scores: ' . json_encode($potentialPanels));

            $primaryPanel = null;
            $secondaryPanel = null;

            foreach ($potentialPanels as $panel => $score) {
                
                if ($panelCounts[$panel] < $maxStudentsPerPanel) {
                    if (!$primaryPanel) {
                        $primaryPanel = $panel;
                        $panelCounts[$panel]++;
                        $primaryPanelScore = $score;
                        logger("Primary panel: {$primaryPanel} with score: {$primaryPanelScore}, Panel count: {$panelCounts[$panel]}");  
                        //$student->update(['panelId' => $primaryPanel]); //panel1Id

                    } elseif (!$secondaryPanel && $primaryPanel !== $panel) {
                        $secondaryPanel = $panel;
                        $panelCounts[$panel]++;  
                        $secondaryPanelScore = $score;
                        logger("Secondary panel: {$secondaryPanel} with score: {$secondaryPanelScore}, Panel count: {$panelCounts[$panel]}");
                        logger('---------------------------------------');
                        break;
                        //$student->update(['panel2Id' => $secondaryPanel]); //panel2Id
                    }

                    // Stop if both primary and secondary panels are assigned
                    if ($primaryPanel && $secondaryPanel) {
                        break;
                    }
                }
            }
        }

        logger('Number of potential panels: ' . count($potentialPanels));
        logger("Number of students: {$totalStudents}");
        logger("maxStudentsPerPanel: {$maxStudentsPerPanel}");
        logger("Final Panel Counts:");
        foreach ($panelCounts as $panel => $count) {
            logger("Panel {$panel}: Count {$count}");
        };
    }
    

    private function assignAvailablePanel($classifier, $features)
    {
        $score = $classifier->predictProbability($features); 

        return $score;
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
