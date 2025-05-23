<?php

namespace App\Services;

use App\Models\User;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Jobs\AssignPanelsToStudentsJob;
use App\Jobs\EmailPanelAssignmentCompleteJob;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AiDataExport;

class CoordinatorService
{
    public function fetchPanelNames(): array
    {
        $response = Http::get('http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=examiner');
        
        if ($response->ok()) {
            return json_decode($response->body(), true);
        }

        throw new \Exception('Failed to fetch lecturer data');
    }

    public function autoAssignPanelsToStudents($psmType, $email)
    {
        $status = "success";

        AssignPanelsToStudentsJob::dispatch($psmType, $email);

        //return response()->json(['message' => 'AI Panel assignment process has started....']);
    }


    public function getPanelHistory(){

        return User::whereHas('panelHistories')
            ->with('panelHistories')
            ->get(['id', 'name','matricNo']);
    }

    public function exportAiData(){

        try {
            return Excel::download(new AiDataExport, 'ai_data.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function removeAllPanelIds(string $studentType)
    {
        try {
            $model = $studentType === 'PSM1' ? StudentPSM1::query() : StudentPSM2::query();

            $model->update([
                'panelId_ai' => null,
                'panel2Id_ai' => null,
            ]);

            return redirect()->back()->with('success', 'All AI panel IDs removed successfully!');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to remove AI panel IDs.');
        }
    }

    
    public function predictAllStudentPanels(string $studentType)
    {
        try {
            $students = $studentType === "PSM1" ? StudentPSM1::all() : StudentPSM2::all();
            
            // Get project area mappings from the database
            $projectAreaMappings = DB::table('project_area_mappings')->get()->keyBy('name');
    
            $panels = DB::table('users')->select('id', 'name','isArchivePSM1','isArchivePSM2')->get();
    
            $allPanels = $panels->pluck('id')->map(fn($id) => $id)->toArray(); //start from 0 to same as $potentialPanels
            $panelName = $panels->pluck('name', 'id')->toArray(); 
    
            $totalStudents = $students->count();
            $totalPanels = count($allPanels);
            
            // Calculate balanced quotas for primary and secondary panel assignments
            $maxStudentsPerPanel = floor($totalStudents / $totalPanels) * 2;
            $maxStudentsPerPrimaryPanel = floor($maxStudentsPerPanel / 2);
            $maxStudentsPerSecondaryPanel = floor($maxStudentsPerPanel / 2);
    
            logger("Total students: {$totalStudents}");
            logger("Total panels: {$totalPanels}");
            logger("Max students per panel (total): {$maxStudentsPerPanel}");
            logger("Max students per primary panel: {$maxStudentsPerPrimaryPanel}");
            logger("Max students per secondary panel: {$maxStudentsPerSecondaryPanel}");

            // dd("here");
    
            // Track primary and secondary assignments separately
            $primaryPanelCounts = array_fill_keys($allPanels, 0);
            $secondaryPanelCounts = array_fill_keys($allPanels, 0);
            
            $results = [];
            $failedPredictions = [];
            
            foreach ($students as $student) {
                try {
                    logger("Student id: {$student->id} [{$student->name}]");
                    
                    $areaName = $student->project_area_ai;
    
                    $primaryPanel = null;
                    $secondaryPanel = null;
                    
                    // Check if the area exists in mappings
                    if (!isset($projectAreaMappings[$areaName])) {
                        $failedPredictions[] = [
                            'student_id' => $student->id,
                            'name' => $student->name,
                            'error' => "Project area mapping not found for: {$areaName}"
                        ];
                        continue;
                    }
    
                    $areaNumber = $projectAreaMappings[$areaName]->number;
                    
                    // Convert project_type to number (0 for System Development, 1 for Research)
                    $typeNumber = ($student->project_type === 'Research Based') ? 1 : 0;
                    
                    // Call prediction API
                    $response = Http::timeout(5)->post('http://127.0.0.1:8001/predict-panel', [
                        'project_area' => $areaNumber,
                        'project_type' => $typeNumber,
                    ]);
                    
                    // Check if the request was successful
                    if ($response->successful()) {
                        $predictions = $response->json()['predictions'];
                        $sorted = collect($predictions)->sortDesc();
    
                        // First pass: assign primary panel
                        foreach($sorted as $panelId => $score) {
                            $panel = $panels->firstWhere('id', $panelId);
    
                            if (!$panel) {
                                logger("Panel ID {$panelId} not found in panel list. Skipping...");
                                continue;
                            }
    
                            // Skip archived panels based on student type
                            if (($studentType === "PSM1" && $panel->isArchivePSM1 == 1) || 
                                ($studentType === "PSM2" && $panel->isArchivePSM2 == 1)) {
                                logger("Panel ID {$panelId} is archived. Skipping...");
                                continue;
                            }
    
                            // Skip supervisor as panel
                            if ($student->supervisorId === $panelId) {
                                continue;
                            }
    
                            // Check if panel has space for primary assignments
                            if ($primaryPanelCounts[$panelId] < $maxStudentsPerPrimaryPanel) {
                                if ($score > 0) {
                                    $primaryPanel = $panelId;
                                    $primaryPanelCounts[$panelId]++;
                                    $student->update(['panelId_ai' => $primaryPanel]);
                                    logger("Primary panel: {$primaryPanel} [{$panelName[$primaryPanel]}], Score: {$score}, Primary count: {$primaryPanelCounts[$panelId]}");
                                    break;
                                }
                            }
                        }
    
                        // If no primary panel was found with score > 0, pick random panel with space
                        if (!$primaryPanel) {
                            $availablePanels = [];
                            foreach ($allPanels as $panelId) {
                                $panel = $panels->firstWhere('id', $panelId);
                                
                                if ($student->supervisorId === $panelId) {
                                    continue;
                                }
                                
                                if (($studentType === "PSM1" && $panel->isArchivePSM1 == 1) || 
                                    ($studentType === "PSM2" && $panel->isArchivePSM2 == 1)) {
                                    continue;
                                }
                                
                                if ($primaryPanelCounts[$panelId] < $maxStudentsPerPrimaryPanel) {
                                    $availablePanels[] = $panelId;
                                }
                            }
                            
                            if (!empty($availablePanels)) {
                                $randomPanelId = $availablePanels[array_rand($availablePanels)];
                                $primaryPanel = $randomPanelId;
                                $primaryPanelCounts[$randomPanelId]++;
                                $student->update(['panelId_ai' => $primaryPanel]);
                                logger("Primary panel (random): {$primaryPanel} [{$panelName[$primaryPanel]}], Primary count: {$primaryPanelCounts[$randomPanelId]}");
                            }else{
                                $randomPanelId = $allPanels[array_rand($allPanels)];
                                $primaryPanel = $randomPanelId;
                                $primaryPanelCounts[$randomPanelId]++;
                                $student->update(['panelId_ai' => $primaryPanel]);
                                logger("Primary panel (random, extra quota): {$primaryPanel} [{$panelName[$primaryPanel]}], Primary count: {$primaryPanelCounts[$randomPanelId]}");

                            }
                        }
    
                        // Second pass: assign secondary panel
                        foreach($sorted as $panelId => $score) {
                            // Skip if it's the same as primary panel or supervisor
                            if ($panelId === $primaryPanel || $panelId === $student->supervisorId) {
                                continue;
                            }
                            
                            $panel = $panels->firstWhere('id', $panelId);
    
                            if (!$panel) {
                                continue;
                            }
    
                            // Skip archived panels
                            if (($studentType === "PSM1" && $panel->isArchivePSM1 == 1) || 
                                ($studentType === "PSM2" && $panel->isArchivePSM2 == 1)) {
                                continue;
                            }
    
                            // Check if panel has space for secondary assignments
                            if ($secondaryPanelCounts[$panelId] < $maxStudentsPerSecondaryPanel) {
                                if ($score > 0) {
                                    $secondaryPanel = $panelId;
                                    $secondaryPanelCounts[$panelId]++;
                                    $student->update(['panel2Id_ai' => $secondaryPanel]);
                                    logger("Secondary panel: {$secondaryPanel} [{$panelName[$secondaryPanel]}], Score: {$score}, Secondary count: {$secondaryPanelCounts[$panelId]}");
                                    break;
                                }
                            }
                        }
    
                        // If no secondary panel was found with score > 0, pick random panel with space
                        if (!$secondaryPanel && $primaryPanel) {
                            $availablePanels = [];
                            foreach ($allPanels as $panelId) {
                                if ($panelId === $primaryPanel || $panelId === $student->supervisorId) {
                                    continue;
                                }
                                
                                $panel = $panels->firstWhere('id', $panelId);
                                
                                if (($studentType === "PSM1" && $panel->isArchivePSM1 == 1) || 
                                    ($studentType === "PSM2" && $panel->isArchivePSM2 == 1)) {
                                    continue;
                                }
                                
                                if ($secondaryPanelCounts[$panelId] < $maxStudentsPerSecondaryPanel) {
                                    $availablePanels[] = $panelId;
                                }
                            }
                            
                            if (!empty($availablePanels)) {
                                $randomPanelId = $availablePanels[array_rand($availablePanels)];
                                $secondaryPanel = $randomPanelId;
                                $secondaryPanelCounts[$randomPanelId]++;
                                $student->update(['panel2Id_ai' => $secondaryPanel]);
                                logger("Secondary panel (random): {$secondaryPanel} [{$panelName[$secondaryPanel]}], Secondary count: {$secondaryPanelCounts[$randomPanelId]}");
                            }else{
                                $randomPanelId = $allPanels[array_rand($allPanels)];
                                $secondaryPanel = $randomPanelId;
                                $secondaryPanelCounts[$randomPanelId]++;
                                $student->update(['panel2Id_ai' => $secondaryPanel]);
                                logger("Secondary panel (random, extra quota): {$secondaryPanel} [{$panelName[$secondaryPanel]}], Secondary count: {$secondaryPanelCounts[$randomPanelId]}");
                            }
                        }
                        
                        logger('---------------------------------------');
                        
                        // Store the prediction results
                        $results[] = [
                            'matric' => $student->matric,
                            'project_area_ai' => $student->project_area_ai,
                            'project_type' => $student->project_type,
                            'predictions' => $sorted->all(),
                            'primary_panel' => $primaryPanel,
                            'secondary_panel' => $secondaryPanel
                        ];
                        
                    } else {
                        $failedPredictions[] = [
                            'student_id' => $student->id,
                            'name' => $student->name,
                            'error' => "API returned error: " . $response->status()
                        ];
                    }
                } catch (\Exception $e) {
                    $failedPredictions[] = [
                        'student_id' => $student->id,
                        'name' => $student->name,
                        'error' => "Exception: " . $e->getMessage()
                    ];
                }
            }
    
            logger(json_encode([
                'success' => true,
                'total_students' => $students->count(),
                'successful_predictions' => count($results),
                'failed_predictions' => count($failedPredictions),
                'failed' => $failedPredictions
            ]));
    
            // Log panel load summary
            logger("Panel assignment summary:");
            foreach ($allPanels as $panelId) {
                $totalCount = $primaryPanelCounts[$panelId] + $secondaryPanelCounts[$panelId];
                logger("Panel ID: {$panelId}, Name: {$panelName[$panelId]}, Primary: {$primaryPanelCounts[$panelId]}, Secondary: {$secondaryPanelCounts[$panelId]}, Total: {$totalCount}");
            }

            // dd($results);

            return back()->with('success', 'AI Panel assignment successfully.');
            
        } catch (\Exception $e) {
            logger(json_encode([
                'error' => $e->getMessage()
            ]));
    
            return back()->with('error', "Error");
        }
    }



}
