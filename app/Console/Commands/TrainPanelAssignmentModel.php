<?php
namespace App\Console\Commands;

use Phpml\Classification\DecisionTree;
use Phpml\ModelManager;
use Illuminate\Console\Command;
use App\Services\ProjectLecturerMergerService;


class TrainPanelAssignmentModel extends Command
{
    protected $signature = 'train:model';
    protected $description = 'Train the decision tree model for panel assignment';

    private $mergerService;

    public function __construct(ProjectLecturerMergerService $mergerService)
    {
        parent::__construct();
        $this->mergerService = $mergerService;
    }
    public function handle()
    {
        // Training Data: Project Area and Project Type for students and corresponding Panel Assignment
        // $samples = [
        //     [0, 0], // AI Research (Student's Project Area: AI, Project Type: Research)
        //     [1, 0], // ML Research (Student's Project Area: ML, Project Type: Research)
        //     [2, 1], // Web Development (Student's Project Area: Web, Project Type: Development)
        //     [0, 1], // AI Development (Student's Project Area: AI, Project Type: Development)
        //     [1, 1], // ML Development (Student's Project Area: ML, Project Type: Development)
        // ];

        // // Labels: Panel IDs (corresponding to the student data)
        // $labels = [1, 2, 3, 1, 2]; // Example panel assignments

        $mergedData = $this->mergerService->mergePanelAndProjectData();

        // Prepare samples and labels
        $samples = [];
        $labels = [];
        $areaMapping = [];
        $typeMapping = [];
        $lecturerMapping = [];

        foreach ($mergedData as $data) {
            $projectArea = $data['project_area'];
            $projectType = $data['project_type'];
            $lecturerName = $data['lecturer_name'];

            // Map project_area to a unique numeric value
            if (!isset($areaMapping[$projectArea])) {
                $areaMapping[$projectArea] = count($areaMapping);
            }

            // Map project_type to a unique numeric value
            if (!isset($typeMapping[$projectType])) {
                $typeMapping[$projectType] = count($typeMapping);
            }

            // Map lecturer_name to a unique numeric value
            if (!isset($lecturerMapping[$lecturerName])) {
                $lecturerMapping[$lecturerName] = count($lecturerMapping);
            }

            $samples[] = [
                $areaMapping[$projectArea],
                $typeMapping[$projectType],
            ];
            $labels[] = $lecturerMapping[$lecturerName];
        }

        // Initialize and train the decision tree model
        $classifier = new DecisionTree();
        $classifier->train($samples, $labels);

        // Save the trained model
        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment.model');
        $modelManager->saveToFile($classifier, $modelPath);

        $this->info("Model trained and saved at: {$modelPath}");
    }
}




