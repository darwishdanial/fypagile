<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ProjectLecturerMergerService
{

    private function fetchPanelData(): array
    {
        $response = Http::get('http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=examiner');

        if ($response->ok()) {

            return json_decode($response->body(), true)['list'];
        }

        throw new \Exception('Failed to fetch panel data');
    }

    public function fetchStudentData(): array
    {
        $response = Http::get('http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=project');

        if ($response->ok()) {

            $body = $response->body();  
        
            $normalizedBody = utf8_encode($body); // Convert to UTF-8

            $cleanedBody = preg_replace('/^b"""/', '', $normalizedBody); // Then clean as usual

            $data = json_decode($cleanedBody, true);

            return $data['list'];

        }else if($response->status() == 429){

            dd('Rate limit exceeded. Please try again later.');
        }

        throw new \Exception('Failed to fetch student data');
    }

    public function getProjectArea(){

        $studentData = $this->fetchStudentData();
        $projectArea[] = [];

        foreach($studentData as $data){
            $projectArea[] = $data['project_area'];
        }

        return $projectArea;
    }

    public function mergePanelAndProjectData(): array{

        $panelData = $this->fetchPanelData();
        $studentData = $this->fetchStudentData();
        $panelMap = [];

        foreach ($panelData as $panel) {
            $id = $panel['id_project_62base'];
            $panelMap[$id][] = $panel['lecturer_name'];
        }

        $categories = [
            'Mobile Application' => ['mobile', 'android', 'ios'],
            'Web Development' => ['web', 'html', 'css', 'javascript', 'frontend', 'backend', 'system', 'ui', 'ux', 'application development', 'app development', 'desktop application'],
            'Machine Learning' => ['machine learning', 'ml', 'ai', 'artificial intelligence', 'processing', 'classification', 'recognition', 'prediction', 'intelligence', 'analytics', 'analysis'],
            'Security' => ['security', 'network security', 'encryption', 'crime', 'fraud', 'scam', 'cryptography', 'biometric'],
            'Augmented Reality' => ['augmented reality', 'ar', 'vr', 'virtual reality', 'reality', 'augmented'],
            'Game Development' => ['game', 'game development', 'gaming'],
            'Management' => ['project management', 'management', 'communication', 'schedule'],
            'Education' => ['education', 'learning', 'teaching'],
            'Networking' => ['network', 'networking', 'sdn', 'wireless mesh', 'iot', 'client server', 'embedded computing', 'internet of things', 'logistic'],
            'Data Science & Analytics' => ['data analytics', 'data visualization', 'data science', 'predictive analysis', 'text mining'],
            'Health & Medical' => ['health', 'medical', 'bioinformatics', 'breast cancer', 'lung cancer', 'pneumonia detection', 'drug discovery', 'cancer drug response', 'medical data', 'hospitality'],
            'Financial & Business' => ['financial', 'stock price', 'investment', 'business', 'e-commerce', 'financial tech', 'fraud detection', 'economic', 'business - investment', 'ecommerce'],
            'Human-Computer Interaction (HCI)' => ['interactive computer graphics', 'human computer interaction', 'hci', 'gesture recognition', 'graphics design', 'usability'],
            'Computer Vision' => ['computer vision', 'object detection', 'facial detection', 'image denoising', 'real-time computer graphics', 'image filtering', 'realtime computer graphics'],
            'Social & Tourism' => ['social', 'tourism', 'accommodation', 'online drivers', 'public transportation', 'travel', 'tourism planning'],
            'Multimedia' => ['multimedia', 'multimedia and hci'],
            'Others' => [] 
        ];

        $mergedData = [];

        foreach ($studentData as $project) {
            $id = $project['id_project_62base'];
            $lecturers = $panelMap[$id] ?? [];

            if (empty($lecturers)) {
                continue;
            }

            // Determine the category based on the project_area
            $projectArea = strtolower($project['project_area']);
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

            // Replace project_area with the matching category
            foreach ($lecturers as $lecturer) {
                $mergedData[] = [
                    'id_project_62base' => $id,
                    'project_area' => $matchedCategory,  // Replacing project_area with the matched category
                    'project_type' => $project['project_type'],
                    'lecturer_name' => $lecturer,
                ];
            }
        }

        return $mergedData;
    }

    public function mergePanelAndProjectDataWithMapping(){

        $mergedData = $this->mergePanelAndProjectData();

        $samples=[];
        $labels=[];
        $samplesWithMapping = [];
        $labelsWithMapping = [];
        $areaMapping = [];
        $typeMapping = [];
        $lecturerMapping = [];
        $panelAssignments = [];
        $panelCount = 0;
        $projectAreaCount = 0;
        $projectTypeCount = 0;

        foreach ($mergedData as $data) {
            $projectArea = $data['project_area'];
            $projectType = $data['project_type'];
            $lecturerName = $data['lecturer_name'];

            if (!isset($areaMapping[$projectArea])) {
                $areaMapping[$projectArea] = count($areaMapping);
                $projectAreaCount++;
            }

            if (!isset($typeMapping[$projectType])) {
                $typeMapping[$projectType] = count($typeMapping);
                $projectTypeCount++;
            }

            if (!isset($lecturerMapping[$lecturerName])) {
                $lecturerMapping[$lecturerName] = count($lecturerMapping);
                $panelAssignments[$lecturerName] = 0;
                $panelCount++;
            }
            $panelAssignments[$lecturerName]++;

            $samplesWithMapping[] = [
                'project_area' => [$areaMapping[$projectArea], $projectArea],
                'project_type' => [$typeMapping[$projectType], $projectType]
            ];

            $labelsWithMapping[] = [$lecturerMapping[$lecturerName], $lecturerName];

            $samples[] = [
                $areaMapping[$projectArea],
                $typeMapping[$projectType],
            ];
            $labels[] = $lecturerMapping[$lecturerName];
        }

        //save areaMappingg and typeMappingg to database

        return [
            'samplesWithMapping' => $samplesWithMapping,
            'labelsWithMapping' => $labelsWithMapping,
            'areaMapping' => $areaMapping,
            'typeMapping' => $typeMapping,
            'lecturerMapping' => $lecturerMapping,
            'panelCount' => $panelCount,
            'projectAreaCount' => $projectAreaCount,
            'projectTypeCount' => $projectTypeCount,
            'samples' => $samples,
            'labels' => $labels,
            'panelAssignments' => $panelAssignments
        ];
    }

    
}
