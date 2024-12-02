<?php
namespace App\Console\Commands;

use Phpml\Classification\DecisionTree;
use Phpml\ModelManager;
use Illuminate\Console\Command;

class TrainPanelAssignmentModel extends Command
{
    protected $signature = 'train:model';
    protected $description = 'Train the decision tree model for panel assignment';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Training Data: Project Area and Project Type for students and corresponding Panel Assignment
        $samples = [
            [0, 0], // AI Research (Student's Project Area: AI, Project Type: Research)
            [1, 0], // ML Research (Student's Project Area: ML, Project Type: Research)
            [2, 1], // Web Development (Student's Project Area: Web, Project Type: Development)
            [0, 1], // AI Development (Student's Project Area: AI, Project Type: Development)
            [1, 1], // ML Development (Student's Project Area: ML, Project Type: Development)
        ];

        // Labels: Panel IDs (corresponding to the student data)
        $labels = [1, 2, 3, 1, 2]; // Example panel assignments

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




