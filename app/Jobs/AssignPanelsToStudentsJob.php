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
use Throwable;

class AssignPanelsToStudentsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $studentType;
    public $email;

    public function __construct($studentType, $email){

        $this->studentType = $studentType;
        $this->email = $email;
    }

    public function handle(){

        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment_svc_linear.model');
        $classifier = $modelManager->restoreFromFile($modelPath);

        $students = null;

        if ($this->studentType === "PSM1" || $this->studentType === "proposal") {
            $students = StudentPSM1::all();
        }elseif($this->studentType === "PSM2"){
            $students = StudentPSM2::all();
        }else {
            throw new \InvalidArgumentException("Invalid student type: {$this->studentType}");
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

        $categories = [
            'Mobile Application' => ['mobile', 'android', 'ios'],
            'Web Development' => ['web', 'html', 'css', 'javascript', 'frontend', 'backend', 'system', 'ui', 'ux', 'application development', 'app development', 'desktop application',],
            'Machine Learning' => ['machine learning', 'ml', 'ai', 'artificial intelligence', 'processing','classification', 'recognition', 'prediction', 'intelligence', 'analytics','analysis'],
            'Security' => ['security', 'network security', 'encryption', 'crime', 'froud', 'scam', 'cryptography', 'biometric'],
            'Augmented Reality' => ['augmented reality', 'ar', 'vr', 'virtual reality', 'reality', 'augmented'],
            'Game Development' => ['game', 'game development', 'gaming'],
            'Management' => ['project management', 'management', 'communication', 'schedule'],
            'Education' => ['education', 'learning', 'teaching'],
            'Networking' => ['network', 'networking', 'sdn', 'wireless mesh', 'iot', 'client server', 'embedded computing', 'internet of things', 'logistic'],
            'Data Science & Analytics' => ['data analytics', 'data visualization', 'data science', 'predictive analysis', 'text mining'],
            'Health & Medical' => ['health', 'medical', 'bioinformatics', 'breast cancer', 'lung cancer', 'pneumonia detection', 'drug discovery', 'cancer drug response', 'medical data', 'hospitality'],
            'Financial & Business' => ['financial', 'stock price', 'investment', 'business', 'e-commerce', 'financial tech', 'fraud detection', 'economic', 'business - investment', 'ecommerce'],
            'Human-Computer Interaction (HCI)' => ['interactive computer graphics','human computer interaction', 'hci', 'gesture recognition', 'graphics design', 'usability'],
            'Computer Vision' => ['computer vision', 'object detection', 'facial detection', 'image denoising', 'real-time computer graphics', 'image filtering', 'realtime computer graphics'],
            'Social & Tourism' => ['social', 'tourism', 'accommodation', 'online drivers', 'public transportation', 'travel', 'tourism planning'],
            'Multimedia' => ['multimedia', 'multimedia and hci'],
            'Others' => [] // A fallback category for any project area that doesn't fit into the predefined categories
        ];

        $projectArea = strtolower($projectArea);
        $category = 'Others'; // Default category

        $matchedCategory = 'Others'; // Default category if no match is found
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($projectArea, $keyword) !== false) {
                    $matchedCategory = $category; // Assign the matched category
                    break 2; // Exit both loops once a match is found
                }
            }
        }

        $areaMapping = ProjectAreaMapping::where('name', $matchedCategory)->first();

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

    // public function failed(?Throwable $exception): void{

    //     logger('Error auto assigning panels to students: ' . $exception->getMessage());
    //     EmailPanelAssignmentCompleteJob::dispatch($this->email, $status = "fail", $exception->getMessage());
    // }
}
