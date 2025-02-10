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
        $mergedData = $this->mergerService->mergePanelAndProjectDataWithMapping();
        $samples = $mergedData['samples'];
        $labels = $mergedData['labels'];

        $classifier = new SVC(
            Kernel::POLYNOMIAL, // Using Polynomial kernel
            1.0,                // Cost parameter (C) //default = 1.0
            4,                  // Polynomial degree (try 2 or 3 first)
            null,               // Gamma (set to null, as it's auto-calculated for poly)
            0.0,                // Coefficient for kernel (default is usually fine)
            0.01,               // Tolerance for stopping criteria
            100,                // Cache size in MB
            true,               // Enable shrinking heuristic
            true                // Enable probability estimates
        );

        $this->info("Training classifier with " . count($samples) . " samples...");
        
        $classifier->train($samples, $labels);

        $this->info("Model training completed!");

        $modelManager = new ModelManager();
        $modelPath = storage_path('app/ai_model/panel_assignment_svc_polinomial_degree4.model');
        $modelManager->saveToFile($classifier, $modelPath);

        $this->info("Model trained and saved at: {$modelPath}");
        
    }
}
