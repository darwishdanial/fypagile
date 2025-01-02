<?php
namespace App\Console\Commands;

use Phpml\Classification\KNearestNeighbors;
use Phpml\ModelManager;
use Illuminate\Console\Command;
use App\Services\ProjectLecturerMergerService;

class KnnPanelAssignmentModel extends Command
{
    protected $signature = 'train:modelknn';
    protected $description = 'Train the KNN model for panel assignment';

    private $mergerService;

    public function __construct(ProjectLecturerMergerService $mergerService)
    {
        parent::__construct();
        $this->mergerService = $mergerService;
    }

    public function handle()
    {
        $mergedData = $this->mergerService->mergePanelAndProjectData();

        $samples = [];
        $labels = [];
        $areaMapping = [];
        $typeMapping = [];
        $lecturerMapping = [];

        foreach ($mergedData as $data) {
            $projectArea = $data['project_area'];
            $projectType = $data['project_type'];
            $lecturerName = $data['lecturer_name'];

            if (!isset($areaMapping[$projectArea])) {
                $areaMapping[$projectArea] = count($areaMapping);
            }

            if (!isset($typeMapping[$projectType])) {
                $typeMapping[$projectType] = count($typeMapping);
            }

            if (!isset($lecturerMapping[$lecturerName])) {
                $lecturerMapping[$lecturerName] = count($lecturerMapping);
            }

            $samples[] = [
                $areaMapping[$projectArea],
                $typeMapping[$projectType],
            ];
            $labels[] = $lecturerMapping[$lecturerName];
        }

        // Using KNearestNeighbors instead of DecisionTree
        $classifier = new KNearestNeighbors();
        $classifier->train($samples, $labels);

        // Save the trained model
        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment_knn.model');
        $modelManager->saveToFile($classifier, $modelPath);

        $this->info("Model trained and saved at: {$modelPath}");
    }
}
