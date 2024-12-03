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

    private function fetchStudentData(): array
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

    public function mergePanelAndProjectData(): array
    {
        $panelData = $this->fetchPanelData();

        $studentData = $this->fetchStudentData();

        $panelMap = [];

        foreach ($panelData as $panel) {
            $id = $panel['id_project_62base'];
            $panelMap[$id][] = $panel['lecturer_name'];
        }

        $mergedData = [];

        foreach ($studentData as $project) {
            $id = $project['id_project_62base'];
            $lecturers = $panelMap[$id] ?? [null]; 

            foreach ($lecturers as $lecturer) {
                $mergedData[] = [
                    'id_project_62base' => $id,
                    'project_area' => $project['project_area'],
                    'project_type' => $project['project_type'],
                    'lecturer_name' => $lecturer,
                ];
            }
        }

        return $mergedData;
    }
}
