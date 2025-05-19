<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
        
            // $normalizedBody = utf8_encode($body); // Convert to UTF-8

            $encoding = mb_detect_encoding($body, mb_list_encodings(), true);

            // If encoding can't be detected, assume a common encoding (like ISO-8859-1)
            if (!$encoding) {
                // Assuming ISO-8859-1 if encoding is not detected
                $encoding = 'ISO-8859-1';
            }
    
            // Convert to UTF-8 using detected or fallback encoding
            $normalizedBody = mb_convert_encoding($body, 'UTF-8', $encoding);

            // try {
            //     $normalizedBody = mb_convert_encoding($body, 'UTF-8', 'auto');
            // } catch (\Exception $e) {
            //     // Log the error or handle gracefully
            //     Log::error('Encoding conversion failed: ' . $e->getMessage());
            //     return [];
            // }

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
    
    public function getCategories(): array
    {
        return [
            'Mobile Application' => ['mobile application','mobile', 'android', 'ios','apps'],
            'Web Development' => ['full','web-based','stack','web', 'html', 'css', 'javascript', 'frontend', 'backend', 'system', 'ui', 'ux', 'application development', 'app development', 'desktop application'],
            'Machine Learning' => ['chatbot','ai-powered','smart','text-mining','autonomous','speech','machine learning', 'ml', 'ai', 'artificial intelligence', 'processing', 'classification', 'recognition', 'prediction', 'intelligence', 'analytics', 'analysis'],
            'Security' => ['penetration','steganography','identifiable','cyber','passcode','security', 'network security', 'encryption', 'crime', 'fraud', 'scam', 'cryptography', 'biometric'],
            'Augmented Reality' => ['augmented reality', 'ar', 'vr', 'virtual reality', 'reality', 'augmented','virtual'],
            'Game Development' => ['game', 'game development', 'gaming'],
            'Management' => ['managing','timetable','scheduling','project management', 'management', 'communication', 'schedule', 'booking'],
            'Education' => ['university','education', 'learning', 'teaching'],
            'Networking' => ['network', 'networking', 'sdn', 'wireless mesh', 'iot', 'client server', 'embedded computing', 'internet of things', 'logistic'],
            'Data Science & Analytics' => ['multi-omics','data analytics', 'data visualization', 'data science', 'predictive analysis', 'text mining'],
            'Health & Medical' => ['biology','donation','counseling','sports','health','fitness','wellness','antimicrobial','cancer','disease','diabetes',',health', 'medical', 'bioinformatics', 'breast cancer', 'lung cancer', 'pneumonia detection', 'drug discovery', 'cancer drug response', 'medical data', 'hospitality'],
            'Financial & Business' => ['fintech','financial', 'stock price', 'investment', 'business', 'e-commerce', 'financial tech', 'fraud detection', 'economic', 'business - investment', 'ecommerce'],
            'Human-Computer Interaction (HCI)' => ['interactive computer graphics', 'human computer interaction', 'hci', 'gesture recognition', 'graphics design', 'usability'],
            'Computer Vision' => ['computer vision', 'object detection', 'facial detection', 'image denoising', 'real-time computer graphics', 'image filtering', 'realtime computer graphics'],
            'Social & Tourism' => ['social', 'tourism', 'accommodation', 'online drivers', 'public transportation', 'travel', 'tourism planning'],
            'Multimedia' => ['multimedia', 'multimedia and hci'],
            'UTM' => ['utm', 'multimedia and hci'],
            'Others' => [] 
        ];
    }

    public function matchCategory(string $projectArea): string 
    {
        // $projectArea = strtolower($projectArea);
        $projectArea = strtolower(trim(preg_replace('/\s+/', ' ', $projectArea)));

        $categories = $this->getCategories();
        
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($projectArea, $keyword) !== false) {
                    return $category;
                }
            }
        }
        
        return 'Others';
    }

    public function mergePanelAndProjectData(): array
    {
        $panelData = $this->fetchPanelData();
        $studentData = $this->fetchStudentData();
        $panelMap = [];
        $matchedCategories = [];

        foreach ($panelData as $panel) {
            $id = $panel['project_id'];
            $panelMap[$id][] = [
                'lecturer_name' => $panel['name'],
                'examiner_status' => $panel['examiner_status']
            ];
        }

        $mergedData = [];

        foreach ($studentData as $project) {
            $id = $project['project_id'];
            $lecturers = $panelMap[$id] ?? [];

            if (empty($lecturers) || empty($project['area'])) {
                continue;
            }

            // Use matchCategory function to determine the category
            $matchedCategory = $this->matchCategory($project['area']);

            if (!isset($matchedCategories[$matchedCategory])) {
                $matchedCategories[$matchedCategory] = [];
            }
            $matchedCategories[$matchedCategory][] = $project['title'];

            foreach ($lecturers as $lecturerInfo) {
                $mergedData[] = [
                    'id_project_62base' => $id,
                    'old_project_area' => $project['area'],
                    'project_area' => $matchedCategory,
                    'project_type' => $project['type'],
                    'lecturer_name' => $lecturerInfo['lecturer_name'],
                ];
            }
        }

        // dd($mergedData);

        // dd($matchedCategories);

        return $mergedData;
    }


    public function mergePanelAndProjectDataWithMapping(bool $user){

        $mergedData = $this->mergePanelAndProjectData();

        // dd($mergedData);

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
        $projectTypeCount = 2;

        foreach ($mergedData as $data) {
            $projectArea = $data['project_area'];
            $projectType = $data['project_type'];
            $lecturerName = $data['lecturer_name'];

            if (!isset($areaMapping[$projectArea])) {
                $areaMapping[$projectArea] = count($areaMapping);
                $projectAreaCount++;
            }

            if (!isset($typeMapping[$projectType])) {
                if ($projectType === 'System Development') {
                    $typeMapping[$projectType] = 0;
                } elseif ($projectType === 'Research Based') {
                    $typeMapping[$projectType] = 1;
                }
            }

            if (!isset($lecturerMapping[$lecturerName])) {
                $lecturerMapping[$lecturerName] = $user ? count($lecturerMapping) + 1 : count($lecturerMapping);
                $panelAssignments[$lecturerName] = 0;
                $panelCount++;
            }
            
            $panelAssignments[$lecturerName]++;

            $samplesWithMapping[] = [
                'project_area' => [$areaMapping[$projectArea], $projectArea],
                'project_type' => [$typeMapping[$projectType], $projectType],
            ];

            $labelsWithMapping[] = [$lecturerMapping[$lecturerName], $lecturerName];

            $samples[] = [
                $areaMapping[$projectArea],
                $typeMapping[$projectType],
            ];
            $labels[] = $lecturerMapping[$lecturerName];
        }

        // dd($labels);

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
