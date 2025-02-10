<?php

namespace App\Services;

use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Metric\Accuracy;
use App\Services\ProjectLecturerMergerService;
use Phpml\Dataset\ArrayDataset;
use Phpml\ModelManager;

class CompareMachineLearningService
{
    protected array $samples;
    protected array $labels;

    public function __construct(ProjectLecturerMergerService $projectLecturerMergerService)
    {
        $data = $projectLecturerMergerService->mergePanelAndProjectDataWithMapping();
        $sample = $data['samples'];
        $label = $data['labels'];

        $this->samples = $sample;
        $this->labels = $label;
    }

    public function compareKernel()
    {
        $datasetaa= new ArrayDataset($this->samples, $this->labels);

        // Split data (70% training, 30% testing)
        $dataset = new StratifiedRandomSplit($datasetaa, 0.3);
        $testSamples = $dataset->getTestSamples();
        $testLabels = $dataset->getTestLabels();

        $modelManagerLinear = new ModelManager();
        $modelPathLinear = storage_path('app/ai_model/panel_assignment_svc_linear.model');
        $classifierLinear = $modelManagerLinear->restoreFromFile($modelPathLinear);

        $modelManagerPolinomial = new ModelManager();
        $modelPathPolinomial = storage_path('app/ai_model/panel_assignment_svc_polinomial_degree3.model');
        $classifierPolinomiald3 = $modelManagerPolinomial->restoreFromFile($modelPathPolinomial);

        $modelManagerPolinomiald2 = new ModelManager();
        $modelPathPolinomiald2 = storage_path('app/ai_model/panel_assignment_svc_polinomial_degree2.model');
        $classifierPolinomiald2 = $modelManagerPolinomiald2->restoreFromFile($modelPathPolinomiald2);

        $modelManagerRbf = new ModelManager();
        $modelPathRbf = storage_path('app/ai_model/panel_assignment_svc_rbf.model');
        $classifierRbf = $modelManagerRbf->restoreFromFile($modelPathRbf);
        
        $modelManagerLinearLinearB4clcfcn = new ModelManager();
        $modelPathLinearB4clcfcn = storage_path('app/ai_model/panel_assignment_svc_linear_b4clsfc.model');
        $classifierRbf = $modelManagerLinearLinearB4clcfcn->restoreFromFile($modelPathLinearB4clcfcn);


        $models=[
            'Linear' => $classifierLinear,
            'Polynomial degree 3' => $classifierPolinomiald3, 
            'Polynomial degree 2' => $classifierPolinomiald2,
            'RBF' => $classifierRbf,
            'Linear b4 clsfx' => $classifierRbf
        ];

        $bestModel = null;
        $bestAccuracy = 0;

        foreach ($models as $name => $classifier) {
            $predictions = $classifier->predict($testSamples);

            $accuracy = Accuracy::score($testLabels, $predictions);

            logger("{$name} Model - Accuracy: {$accuracy}");

            // Select model based on highest accuracy
            if ($accuracy > $bestAccuracy) {
                $bestAccuracy = $accuracy;
                $bestModel = $name;
            }

        }

        return [
            'best accuracy' => $bestAccuracy,
            'best model' => $bestModel
        ];
    }

}
