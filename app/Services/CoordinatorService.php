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
    public function getStudentPSM1()
    {
        return StudentPSM1::all();
    }

    public function getStudentPSM2()
    {
        return StudentPSM2::all();
    }

    public function getTotalMarksPSM1()
    {
        // Retrieve the data from the result_psm1 table
        $results = DB::table('result_psm1')->get();

        foreach ($results as $result) {
            $studentId = $result->studentId;
            $type = $result->type;
            $total = round($result->total, 2);

            // Check if the student record already exists in the result_totalpsm1 table
            $existingRecord = DB::table('result_totalpsm1')->where('studentId', $studentId)->first();

            if ($existingRecord) {
                // Update the existing record based on the type
                if ($type === 'supervisor') {
                    DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['sv' => $total]);
                } elseif ($type === 'panel') {
                    if ($existingRecord->panel1) {
                        DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['panel2' => $total]);
                    } else {
                        DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['panel1' => $total]);
                    }
                }
            } else {
                // Create a new record in the result_totalpsm1 table
                $data = [
                    'studentId' => $studentId,
                    'sv' => $type === 'sv' ? $total : null,
                    'panel1' => $type === 'panel' ? $total : null,
                    'panel2' => $type === 'panel' ? $total : null,
                ];

                DB::table('result_totalpsm1')->insert($data);
            }
        }

        // Calculate the total marks
        $students = DB::table('result_totalpsm1')->get();

        foreach ($students as $student) {
            $studentId = $student->studentId;
            $sv = $student->sv;
            $panel1 = $student->panel1;
            $panel2 = $student->panel2;

            // Calculate the total marks and update the result_totalpsm1 table
            $totalMarks = round($sv + $panel1 + $panel2, 2);
            DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['totalmarks' => $totalMarks]);
        }

        return "Data inserted and total marks calculated successfully.";
    }

    public function getResultPSM1()
    {
        // Fetch and calculate the results for PSM1 students
        $results = DB::table('result_psm1')
            ->get();
        // List all students in results
        $students = $results->map(function($result) {
            return StudentPSM1::find($result->studentId);
        })->unique();

        // Iterate student
        // Create object containing sv, panel and total score with student id and name
        $totalResult = $students->map(function($student) use ($results){
            // error_log($student);
            $result = $results->where('studentId', $student->id);
            $supervisor = $result->where('type', 'supervisor')->map(function($svResult){ return $svResult->total; })->first();
            // $panels = $result->where('type', 'panel')->map(function($panelResult){ return $panelResult->total; });
            $panel1 = $result->where('type', 'panel1')->map(function($panelResult){ return $panelResult->total; })->first();
            $panel2 = $result->where('type', 'panel2')->map(function($panelResult){ return $panelResult->total; })->first();
            $coordinator = $result->where('type', 'coordinator')->map(function($coordinatorResult){ return $coordinatorResult->total; })->first();
            // $total = $supervisor + $panels->reduce(function($carry, $panel){ return $carry + $panel; });
            $total = $supervisor + $panel1 + $panel2 + $coordinator;
            $total = $total == 0 ? null : $total;

            return [
                'id' => $student->id,
                'name' => $student->name,
                'sv' => $supervisor,
                'panel1' => $panel1,
                'panel2' => $panel2,
                'coordinator' => $coordinator,
                'totalmarks' => $total,
            ];
        })->values();

        return $totalResult;
        // error_log('$totalResult');
        // error_log($totalResult);
            // dd($result);
    }

    public function getResultPSM2(){
        // Lists all results without any filter
        $results = DB::table('result_psm2')
            ->get();

        // List all students in results
        $students = $results->map(function($result) {
            return StudentPSM2::find($result->studentId);
        })->unique();

        // Iterate student
        // Create object containing sv, panel and total score with student id and name
        $totalResult = $students->map(function($student) use ($results){
            // error_log($student);
            $result = $results->where('studentId', $student->id);
            $supervisor = $result->where('type', 'supervisor')->map(function($svResult){ return $svResult->total; })->first();
            // $panels = $result->where('type', 'panel')->map(function($panelResult){ return $panelResult->total; });
            $panel1 = $result->where('type', 'panel1')->map(function($panelResult){ return $panelResult->total; })->first();
            $panel2 = $result->where('type', 'panel2')->map(function($panelResult){ return $panelResult->total; })->first();
            $coordinator = $result->where('type', 'coordinator')->map(function($coordinatorResult){ return $coordinatorResult->total; })->first();
            // $total = $supervisor + $panels->reduce(function($carry, $panel){ return $carry + $panel; });
            $total = $supervisor + $panel1 + $panel2 + $coordinator;
            $total = $total == 0 ? null : $total;

            return [
                'id' => $student->id,
                'name' => $student->name,
                'sv' => $supervisor,
                'panel1' => $panel1,
                'panel2' => $panel2,
                'coordinator' => $coordinator,
                'totalmarks' => $total,
            ];
        })->values();

        return $totalResult;
        // error_log('$totalResult');
        // error_log($totalResult);
    }

    public function editstudentPSM1($id){

        $student = StudentPSM1::where('id', $id)->first();
        if (!$student) {
            // Handle the case when the student is not found
            // For example, you could redirect the user back with an error message
            return redirect()->back()->with('error', 'Student not found.');
        }
        // echo "Student: $student";
       
        $data = ['student' => $student];

        return $data;
    }

    public function editstudentPSM2($id){

        $student = StudentPSM2::where('id', $id)->first();
        if (!$student) {
            // Handle the case when the student is not found
            // For example, you could redirect the user back with an error message
            return redirect()->back()->with('error', 'Student not found.');
        }
        // echo "Student: $student";
       
        $data = ['student' => $student];

        return $data;
    }

    public function viewResultPSM1($id){

        $student = DB::table('result_psm1')->where('studentId', $id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }


        $results = DB::table('result_psm1')
        ->where('studentId', $student->studentId)
        ->orderByRaw("FIELD(type, 'supervisor', 'panel', 'coordinator')")
        ->get();

        $data = [
            'student' => $student,
            'results' => $results,
        ];

        return $data;
    }

    public function viewResultPSM2($id){
        $student = DB::table('result_psm2')->where('studentId', $id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }


        $results = DB::table('result_psm2')
        ->where('studentId', $student->studentId)
        ->orderByRaw("FIELD(type, 'supervisor', 'panel', 'coordinator')")
        ->get();

        $data = [
            'student' => $student,
            'results' => $results,
        ];

        return $data;
    }

    public function getMarkahPSM1Coordinator($data){
                
        $ethics = ($data['similarity']/4*2)+($data['similarity']/4*3); //5%

        $studentId = $data['id'];
        $typeId = Session::get('id');
        // $panelType = (StudentPSM1::find($studentId)->first()->panelId == $typeId) ? 'panel1' : 'panel2';
        $panelType = 'coordinator';

        $existingRecord = DB::table('result_psm1')
            ->where('studentId', $studentId)
            ->where('typeId', $typeId)
            ->first();
    
        if ($existingRecord) {
            // Update the existing record
            DB::table('result_psm1')
                ->where('studentId', $studentId)
                ->where('typeId', $typeId)
                ->update([
                    'ethics' =>$ethics,
                    'total' => $ethics,
                ]);
        } else {
            // Insert a new record
            DB::table('result_psm1')->insert([
                'studentId' => $studentId,
                'typeId' => $typeId,
                'type' => $panelType,
                'ethics' =>$ethics,
                'total' => $ethics,
            ]);
        }
        
    }

    public function getMarkahPSM2Coordinator($data){

        $ethics = ($data['similarity']/4*2)+($data['similarity']/4*3); //5%

        $studentId = $data['id'];
        $typeId = Session::get('id');
        // $panelType = (StudentPSM1::find($studentId)->first()->panelId == $typeId) ? 'panel1' : 'panel2';
        $panelType = 'coordinator';

        $existingRecord = DB::table('result_psm2')
            ->where('studentId', $studentId)
            ->where('typeId', $typeId)
            ->first();
    
        if ($existingRecord) {
            // Update the existing record
            DB::table('result_psm2')
                ->where('studentId', $studentId)
                ->where('typeId', $typeId)
                ->update([
                    'ethics' =>$ethics,
                    'total' => $ethics,
                ]);
        } else {
            // Insert a new record
            DB::table('result_psm2')->insert([
                'studentId' => $studentId,
                'typeId' => $typeId,
                'type' => $panelType,
                'ethics' =>$ethics,
                'total' => $ethics,
            ]);
        }
    
    }

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

        // AssignPanelsToStudentsJob::withChain([
        //     new EmailPanelAssignmentCompleteJob($email, $status),
        // ])->dispatch($psmType, $email);

        AssignPanelsToStudentsJob::dispatch($psmType, $email);

        //return response()->json(['message' => 'AI Panel assignment process has started....']);
    }

    public function deleteAllAssignedPanels(){

        StudentPSM1::query()->update([
            'panelId' => null,
            'panel2Id' => null,
        ]);
    
        return response()->json(['message' => 'All panel assignments have been cleared successfully.']);
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

    public function removeAllPanelIdsFromPSM1()
    {
        StudentPSM1::query()->update([
            'panelId_ai' => null,
            'panel2Id_ai' => null,
        ]);

        return redirect()->back()->with('success', 'All AI panel IDs removed successfully!');
    }

    public function removeAllPanelIdsFromPSM2()
    {
        StudentPSM2::query()->update([
            'panelId_ai' => null,
            'panel2Id_ai' => null,
        ]);

        return redirect()->back()->with('success', 'All AI panel IDs removed successfully!');
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
            $maxStudentsPerPanel = ceil($totalStudents / $totalPanels) * 2;

            logger("Total students: {$totalStudents}");
            logger("Total panels: {$totalPanels}");
            logger("Max students per panel: {$maxStudentsPerPanel}");

            $panelCounts = array_fill_keys($allPanels, 0); //keep track of student count per panel
    
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
                    $typeNumber = ($student->project_type === 'Research') ? 1 : 0;
                    
                    // Call prediction API
                    $response = Http::timeout(5)->post('http://127.0.0.1:8001/predict-panel', [
                        'project_area' => $areaNumber,
                        'project_type' => $typeNumber,
                    ]);
                    
                    // Check if the request was successful
                    if ($response->successful()) {
                        $predictions = $response->json()['predictions'];
                        $sorted = collect($predictions)->sortDesc();

                        foreach($sorted as $panelId => $score) {

                            if ($panelCounts[$panelId] < $maxStudentsPerPanel && $student->supervisorId !== $panelId) {

                                $panel = $panels->firstWhere('id', $panelId);

                                if (!$panel) {
                                    logger("Panel ID {$panelId} not found in panel list. Skipping...");
                                    continue;
                                }

                                if ($studentType === "PSM1" && $panel->isArchivePSM1 == 1) {
                                    logger("Panel ID {$panelId} is archived. Skipping...");
                                    continue; // Skip archived panels for PSM1
                                }
                                if ($studentType === "PSM2" &&  $panel->isArchivePSM2 == 1){
                                    logger("Panel ID {$panelId} is archived. Skipping...");
                                    continue; // Skip archived panels for PSM2
                                }

                                switch ($score) {
                                    case 0:
                                        // Assign random available panels if the score is 0
                                        $availablePanels = array_filter($panelCounts, fn($count) => $count < $maxStudentsPerPanel);
                                
                                        if (!empty($availablePanels)) {
                                            // Assign primary panel
                                            if (!$primaryPanel) {
                                                $randomPanelId = array_rand($availablePanels);
                                                $primaryPanel = $randomPanelId;
                                                $panelCounts[$randomPanelId]++;
                                                $student->update(['panelId_ai' => $primaryPanel]);
                                                logger("Primary panel (random due to score 0): {$primaryPanel} [{$panelName[$primaryPanel]}], Panel count: {$panelCounts[$randomPanelId]}");
                                
                                                // Remove the assigned panel from available panels
                                                unset($availablePanels[$randomPanelId]);
                                            }
                                
                                            // Assign secondary panel
                                            if (!$secondaryPanel && !empty($availablePanels)) {
                                                $randomPanelId = array_rand($availablePanels);
                                                $secondaryPanel = $randomPanelId;
                                                $panelCounts[$randomPanelId]++;
                                                $student->update(['panel2Id_ai' => $secondaryPanel]);
                                                logger("Secondary panel (random due to score 0): {$secondaryPanel} [{$panelName[$secondaryPanel]}], Panel count: {$panelCounts[$randomPanelId]}");
                                                logger('---------------------------------------');
                                            }
                                        }
                                
                                        break;
                                
                                    default:
                                        if (!$primaryPanel) {
                                            $primaryPanel = $panelId;
                                            $panelCounts[$panelId]++;
                                            $student->update(['panelId_ai' => $primaryPanel]);
                                            logger("Primary panel: {$primaryPanel} [{$panelName[$primaryPanel]}], Score: {$score}, Panel count: {$panelCounts[$panelId]}");
                                        } elseif (!$secondaryPanel && $primaryPanel !== $panelId) {
                                            $secondaryPanel = $panelId;
                                            $panelCounts[$panelId]++;
                                            $student->update(['panel2Id_ai' => $secondaryPanel]);
                                            logger("Secondary panel: {$secondaryPanel} [{$panelName[$secondaryPanel]}], Score: {$score}, Panel count: {$panelCounts[$panelId]}");
                                            logger('---------------------------------------');
                                            break;
                                        }
                                
                                        break;
                                }
                                
                            }
                        }
                        
                        // Store the prediction results
                        $results[] = [
                            'matric' => $student->matric,
                            'project_area_ai' => $student->project_area_ai,
                            'project_type' => $student->project_type,
                            'predictions' => $sorted->all()
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

            // dd("success");

            logger(json_encode([
                'success' => true,
                'total_students' => $students->count(),
                'successful_predictions' => count($results),
                'failed_predictions' => count($failedPredictions),
                // 'results' => $results,
                'failed' => $failedPredictions
            ]));

            foreach ($panelCounts as $panelId => $count) {
                logger("Panel ID: {$panelId}, Name: {$panelName[$panelId]}, Student Count: {$count}");
            }

            return back()->with('success', 'AI Panel assignment successfully.');
            
        } catch (\Exception $e) {

            logger(json_encode([
                'error' => $e->getMessage()
            ]));

            return back()->with('error', $e->getMessage());
        }
    }



}
