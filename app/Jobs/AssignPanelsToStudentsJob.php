<?php

namespace App\Jobs;

use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Phpml\ModelManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ProjectAreaMapping;
use Illuminate\Bus\Queueable;

class AssignPanelsToStudentsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $studentType;

    public function __construct($studentType){

        $this->studentType = $studentType;
    }

    public function handle(){

        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment_svc.model');
        $classifier = $modelManager->restoreFromFile($modelPath);

        $students = null;

        if ($this->studentType === "PSM1" || $this->studentType === "proposal") {
            $students = StudentPSM1::all();
        }elseif($this->tudentType = "PSM2"){
            $students = StudentPSM2::all();
        }

        $panels = DB::table('users')->select('id', 'name')->get();

        $allPanels = $panels->pluck('id')->map(fn($id) => $id - 1)->toArray(); //start from 0 to same as $potentialPanels
        $panelName = $panels->pluck('name', 'id')->toArray(); 

        $totalStudents = $students->count();
        $totalPanels = count($allPanels);
        $maxStudentsPerPanel = ceil($totalStudents / $totalPanels) * 2;
        $panelCounts = array_fill_keys($allPanels, 0); //keep track of student count per panel

        foreach ($students as $student) {

            logger("Student id: {$student->id} [{$student->name}]");

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

            $loadFactors = [];
            foreach ($potentialPanels as $panel => $score) {
                $currentLoad = $panelCounts[$panel];
                $loadRatio = $currentLoad / $maxStudentsPerPanel;
                
                // Adjust score based on panel load
                // As panel gets more loaded, its effective score decreases
                $loadPenalty = $loadRatio * 0.5; // Adjust this factor to control distribution vs. compatibility
                $adjustedScore = $score * (1 - $loadPenalty);
                
                $loadFactors[$panel] = $adjustedScore;
            }

            // Sort panels by adjusted scores
            arsort($loadFactors);

            foreach ($loadFactors as $panel => $adjustedScore) {

                if ($panelCounts[$panel] < $maxStudentsPerPanel) {
                    if (!$primaryPanel) {
                        $primaryPanel = $panel + 1;
                        $panelCounts[$panel]++;
                        //$student->update(['panelId' => $primaryPanel]); 
                        logger("Primary panel: {$primaryPanel} [{$panelName[$primaryPanel]}] with original score: {$potentialPanels[$panel]}, Adjusted score: {$adjustedScore}, Panel count: {$panelCounts[$panel]}");  

                    } elseif (!$secondaryPanel && $primaryPanel !== $panel) {
                        $secondaryPanel = $panel + 1;
                        $panelCounts[$panel]++;  
                        //$student->update(['panel2Id' => $secondaryPanel]); 
                        logger("Secondary panel: {$secondaryPanel} [{$panelName[$secondaryPanel]}] with original score: {$potentialPanels[$panel]}, Adjusted score: {$adjustedScore}, Panel count: {$panelCounts[$panel]}");
                        logger('---------------------------------------');
                        break;
                    }
                }
            }
        }

        $this->logDistributionStats($panelCounts, $maxStudentsPerPanel, $totalStudents, $panelName);
    }

    private function logDistributionStats($panelCounts, $maxStudentsPerPanel, $totalStudents, $panelName){

        logger("Distribution Statistics:");
        logger("Total Students: {$totalStudents}");
        logger("Total Panels: " . count($panelCounts));
        logger("Target Students Per Panel: {$maxStudentsPerPanel}");
        
        $min = min($panelCounts);
        $max = max($panelCounts);
        $avg = array_sum($panelCounts) / count($panelCounts);
        $variance = $this->calculateVariance($panelCounts, $avg);
        
        logger("Min assignments: {$min}");
        logger("Max assignments: {$max}");
        logger("Average assignments: {$avg}");
        logger("Assignment variance: {$variance}");
        
        foreach ($panelCounts as $panel => $count) {
            $deviation = $count - $maxStudentsPerPanel;
            $panelId = $panel + 1;
            logger("Panel {$panelId} [{$panelName[$panelId]}]: Student Count {$count} (Deviation: {$deviation})");
        }
    }

    private function calculateVariance($panelCounts, $mean){

        $squaredDiffs = array_map(function($count) use ($mean) {
            return pow($count - $mean, 2);
        }, $panelCounts);
        
        return array_sum($squaredDiffs) / count($panelCounts);
    }
    

    private function assignAvailablePanel($classifier, $features){

        $score = $classifier->predictProbability($features); 

        return $score;
    }


    private function getAreaNumericValue($projectArea){

        $areaMapping = ProjectAreaMapping::where('name', $projectArea)->first();

        return $areaMapping ? $areaMapping->number : -1;
    }

    private function getTypeNumericValue($projectType){
        
        if ($projectType == 'System Development') {
            return 0; // System Development => 0
        } elseif ($projectType == 'Research Based') {
            return 1; // Research Based => 1
        }
        return -1; // Default to -1 if not matching
    }
}
