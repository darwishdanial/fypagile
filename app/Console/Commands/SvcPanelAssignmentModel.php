<?php

namespace App\Console\Commands;

use Phpml\Classification\SVC; // Import the SVC classifier
use Phpml\SupportVectorMachine\Kernel; // Import Kernel class for SVC
use Phpml\ModelManager;
use Illuminate\Console\Command;
use App\Services\ProjectLecturerMergerService;

class SvcPanelAssignmentModel extends Command
{
    protected $signature = 'train:modelsvc';
    protected $description = 'Train the SVC model for panel assignment';

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

        // Using Support Vector Classification (SVC) with probability estimates enabled
        $classifier = new SVC(
            Kernel::LINEAR,      // Use linear kernel
            1.0,                 // Cost parameter
            3,                   // Degree of the polynomial kernel (if using polynomial)
            null,                // Gamma (optional)
            0.0,                 // Coefficient for kernel
            0.01,               // Tolerance
            100,                 // Cache size
            true,                // Shrinking enabled
            true                 // Enable probability estimates
        );

        $this->info("Training classifier with " . count($samples) . " samples...");
        
        $classifier->train($samples, $labels);

        $this->info("Model training completed!");

        // Save the trained model
        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment_svc.model');
        $modelManager->saveToFile($classifier, $modelPath);

        $this->info("Model trained and saved at: {$modelPath}");
    }
}
