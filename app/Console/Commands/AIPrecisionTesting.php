<?php
namespace App\Console\Commands;

use Phpml\Classification\DecisionTree;
use Phpml\ModelManager;
use Illuminate\Console\Command;
use App\Services\ProjectLecturerMergerService;
use Illuminate\Support\Facades\Http;
use App\Services\CoordinatorService;
use Illuminate\Support\Facades\DB;


class AIPrecisionTesting extends Command
{
    protected $signature = 'test:model';
    protected $description = 'Test the panel assigment model for precision';

    private $mergerService;
    protected $coordinatorService;


    public function __construct(ProjectLecturerMergerService $mergerService, CoordinatorService $coordinatorService)
    {
        parent::__construct();
        $this->mergerService = $mergerService;
        $this->coordinatorService = $coordinatorService;
    }
    public function handle()
    {

        $this->info("Starting AI Model Testing...");

        $studentData = $this->mergerService->fetchStudentData();
        $users = $this->coordinatorService->getPanelHistory();
        $matchCount = 0;
        $errorCount = 0;
        $totalPredictions = 0;
        $errorLecturers = [];

        foreach ($studentData as $student) {

            if (empty($student['type']) || empty($student['area'])) {
                continue;
            }

            $projectArea = $this->mergerService->matchCategory($student['area']);

            $projectAreaMappings = DB::table('project_area_mappings')->get()->keyBy('name');

            $areaNumber = $projectAreaMappings[$projectArea]->number;

            $typeNumber = ($student['type'] === 'Research Based') ? 1 : 0;

            // logger($projectArea);
            
            $response = Http::timeout(5)->post('http://127.0.0.1:8001/predict-panel', [
                'project_area' => $areaNumber,
                'project_type' => $typeNumber,
            ]);

            if ($response->successful()) {
                $predictions = $response->json()['predictions'];
                $sorted = collect($predictions)->sortDesc();
                $top3 = $sorted->take(3);
                // $this->info("Top 3: {$top3}");
                $count = 1;
                
                // Check each of the top 3 predictions
                foreach ($top3 as $panelId => $score) {

                    $panelHistory = $users->firstWhere('id', $panelId);
                    if ($panelHistory) {
                        $historicalAssignments = $panelHistory->panelHistories;
                        
                        // Check if panel has experience with similar projects
                        $hasMatchingHistory = $historicalAssignments->contains(function ($history) use ($student, $projectArea) {
                            return $history->project_area === $projectArea && 
                                $history->project_type === $student['type'];
                        });
                        
                        if ($hasMatchingHistory) {
                            $matchCount++;
                        }else{
                            $errorCount++;
                            $panelName = $users->firstWhere('id', $panelId)->name;
                            $errorLecturers[] = [
                                'name' => $panelName,
                            ];
                            logger("Panel no: {$count} ## Project area: {$projectArea} ## Project type: {$student['type']} ## Panel: {$panelName}");
                        }
                    } else {
                        logger("No panel history found for panel ID: {$panelId}");
                    }
                    
                    $totalPredictions++;
                    $count++;
                }
            }else{
                $this->error("Failed to fetch predictions for student: {$student['project_id']}");
                continue;
            }
        }

        $accuracy = ($totalPredictions > 0) ? ($matchCount / $totalPredictions) * 100 : 0;
        $this->info("Prediction accuracy: {$accuracy}%");
        $this->info("Matches found: {$matchCount} out of {$totalPredictions} predictions");
        $this->info("Error count: {$errorCount}");

        // Display error lecturers
        if (!empty($errorLecturers)) {
            $this->info("\nLecturers with mismatched assignments:");
            
            // Count occurrences of each lecturer
            $lecturerCounts = collect($errorLecturers)
                ->groupBy('name')
                ->map(function ($group) {
                    $item = $group->first();
                    return [
                        'name' => $item['name'],
                        'count' => $group->count()
                    ];
                })
                ->sortByDesc('count')
                ->values();

            $this->table(
                ['Lecturer Name', 'Error Count'],
                $lecturerCounts->map(function ($item) {
                    return [
                        $item['name'],
                        $item['count']
                    ];
                })->toArray()
            );
        }
    }
}




