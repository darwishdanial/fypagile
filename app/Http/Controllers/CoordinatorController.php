<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Illuminate\Http\Request;
use App\Services\ProjectLecturerMergerService;
use App\Services\CoordinatorService;
use App\Services\StudentService;
use App\Services\PanelService;
use App\Services\SupervisorService;
use App\Services\CompareMachineLearningService;
use App\Jobs\EmailPanelAssignmentCompleteJob;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Exports\AiDataExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class CoordinatorController extends Controller
{
    protected $coordinatorService;
    protected $studentService;
    protected $panelService;
    protected $supervisorService;

    public function __construct(CoordinatorService $coordinatorService, StudentService $studentService, PanelService $panelService, SupervisorService $supervisorService){

        $this->coordinatorService = $coordinatorService;

        $this->studentService = $studentService;

        $this->panelService = $panelService;

        $this->supervisorService = $supervisorService;
    }

    //Home

    public function index()
    {
        $this->authorize('view coordinator dashboard');

        $userName = Auth::user()->name;
        $studentsPSM1 = $this->studentService->getStudentPSM1()->count();
        $studentsPSM2 = $this->studentService->getStudentPSM2()->count();
        $panelsPSM1 = $this->panelService->getPanelPSM1()->count();
        $panelsPSM2 = $this->panelService->getPanelPSM2()->count();

        return Inertia::render('Coordinator/Home/Index',[
            'userName' => $userName,
            'studentsPSM1' => $studentsPSM1,
            'studentsPSM2' => $studentsPSM2,
            'panelsPSM1' => $panelsPSM1,
            'panelsPSM2' => $panelsPSM2

        ]);
    }

    //SUPERVISOR ASSIGN
    //PSM1

    public function PSM1ListSupervisor()
    {
        $this->authorize('view psm1 assign supervisor table');

        $supervisors = $this->supervisorService->getSupervisorPSM1();

        //dd($supervisors);

        return Inertia::render('Coordinator/PSM1/AssignSupervisor',[
            'supervisor' => $supervisors
        ]);
    }

    public function PSM1SupervisorStudentList($id)
    {
        $students = $this->studentService->getStudentsSupervisorPSM1($id);

        return $students;
    }

    public function PSM1SAssignSupervisor(Request $request)
    {
        $studentId = $request->input('studentId');
        $supervisorId = $request->input('supervisorId');
        
        $this->studentService->assignStudentsSupervisorPSM1($studentId, $supervisorId);
    }

    public function PSM1UnassignSupervisor($studentId)
    {
        $this->studentService->unassignStudentsSupervisorPSM1($studentId);
    }


    //SUPERVISOR ASSIGN
    //PSM2

    public function PSM2ListSupervisor()
    {
        $supervisors = $this->supervisorService->getSupervisorPSM2();

        //dd($supervisors);

        return Inertia::render('Coordinator/PSM2/AssignSupervisorPSM2',[
            'supervisor' => $supervisors
        ]);
    }

    public function PSM2SupervisorStudentList($id)
    {
        $students = $this->studentService->getStudentsSupervisorPSM2($id);

        return $students;
    }

    public function PSM2SAssignSupervisor(Request $request)
    {
        $studentId = $request->input('studentId');
        $supervisorId = $request->input('supervisorId');
        
        $this->studentService->assignStudentsSupervisorPSM2($studentId, $supervisorId);
    }

    public function PSM2UnassignSupervisor($studentId)
    {
        $this->studentService->unassignStudentsSupervisorPSM2($studentId);
    }

    //PANEL ASSIGN
    //PSM1

    public function PSM1listAssignPanel()
    {
        $this->authorize('view psm1 assign panel table');

        $panels = $this->panelService->getAssignPanelPSM1();

        return Inertia::render('Coordinator/PSM1/AssignPSM1Panel',[
            'panel' => $panels
        ]);
    }

    public function PSM1PanelStudentList(Request $request)
    {
        $panelId = $request->input('panelId');
        $type = $request->input('panelTypeValue');

        $students = $this->studentService->getStudentsPSM1lPanel($panelId, $type);

        return $students;
    }

    public function PSM1SAssignPanel1(Request $request)
    {
        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');
        
        $this->studentService->assignStudentsPSMPanel1PSM1($studentId, $panelId);
    }

    public function PSM1SAssignPanel2(Request $request)
    {
        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');
        
        $this->studentService->assignStudentsPSMPanel2PSM1($studentId, $panelId);
    }

    public function PSM1UnassignPSMPanel1($studentId)
    {
        $this->studentService->unassignStudentsPSMPanel1PSM1($studentId);
    }

    public function PSM1UnassignPSMPanel2($studentId)
    {
        $this->studentService->unassignStudentsPSMPanel2PSM1($studentId);
    }

    public function PSM1autoAssignPanelsToStudents(){

        $user = Auth::user();
        $email = $user->email;
        $psmType = 'PSM1';
        $this->coordinatorService->autoAssignPanelsToStudents($psmType, $email);

        return redirect()->back()->with(['success' => 'AI Panel assignment process has started....']);

    }

    //PANEL ASSIGN
    //PSM2

    public function PSM2ListPanel()
    {
        $this->authorize('view psm2 assign panel table');

        $panels = $this->panelService->getAssignPanelPSM2();

        return Inertia::render('Coordinator/PSM2/AssignPSM2Panel',[
            'panel' => $panels
        ]);
    }
    public function PSM2PanelStudentList(Request $request)
    {
        $panelId = $request->input('panelId');
        $type = $request->input('panelTypeValue');

        $students = $this->studentService->getStudentsPSM2lPanel($panelId, $type);

        return $students;
    }

    public function PSM2SAssignPanel1(Request $request)
    {
        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');
        
        $this->studentService->assignStudentsPSMPanel1PSM2($studentId, $panelId);
    }

    public function PSM2SAssignPanel2(Request $request)
    {
        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');
        
        $this->studentService->assignStudentsPSMPanel2PSM2($studentId, $panelId);
    }

    public function PSM2UnassignPSMPanel1($studentId)
    {
        $this->studentService->unassignStudentsPSMPanel1PSM2($studentId);
    }

    public function PSM2UnassignPSMPanel2($studentId)
    {
        $this->studentService->unassignStudentsPSMPanel2PSM2($studentId);
    }

    //AI

    public function getMLData(ProjectLecturerMergerService $mergerService){

        $projectArea = $mergerService->mergePanelAndProjectDataWithMapping(true);
        $sampleCount = count($projectArea['samples']);
        $labelCount = count($projectArea['labels']);
        $samples = $projectArea['samples'];
        $labels = $projectArea['labels'];

        return Inertia::render('Coordinator/Home/MLData',[
            'totalPanel' => $projectArea['panelCount'],
            'totalProjectArea' =>$projectArea['projectAreaCount'],
            'totalProjectType' => $projectArea['projectTypeCount'],
            'totalSamples' => $sampleCount,
            'totalLabels' => $labelCount,
            'asgCountPerPanel' => $projectArea['panelAssignments'],
        ]);

        
        // logger(json_encode($samples, JSON_PRETTY_PRINT));
        // logger(json_encode($labels, JSON_PRETTY_PRINT));

        // dd([
        //     'Total Panel (Label Count)' => $projectArea['panelCount'],  
        //     'Total Project Areas' => $projectArea['projectAreaCount'],  
        //     'Total Project Types' => $projectArea['projectTypeCount'],  
        //     'Total Samples' => $sampleCount,  
        //     'Total Labels' => $labelCount,
        //     'Asg count per panel' => $projectArea['panelAssignments'],
        // ]);


        //103 panel -> label type
        //16 project area
        //2 project type
        //2567 samples and labels

        //low probability because large number of panels

    }

    public function compareKernelModel(CompareMachineLearningService $compareMachineLearningService){
        $mergeResult = $compareMachineLearningService->compareKernel();
        dd($mergeResult);
    }

    public function getPanelHistory(){

        $users = User::whereHas('panelHistories')
                ->with('panelHistories')
                ->get(['id', 'name','matricNo']);

        //  dd($users[0]);

        return Inertia::render('Coordinator/Home/PanelHistory', [
            'users' => $users
        ]);

    }

    public function exportAiData()
    {
        // return Excel::download(new AiDataExport, 'ai_data.xlsx');

        try {
            return Excel::download(new AiDataExport, 'ai_data.xlsx');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function getSampleData(ProjectLecturerMergerService $mergerService){

        $mergerService->mergePanelAndProjectDataWithMapping(true);
    }

    public function testPanelApi(){

        // $response = Http::timeout(5)->post('http://127.0.0.1:8001/predict-panel', [
        //     'project_area' => 3,
        //     'project_type' => 1,
        // ]);
        
        // $predictions = $response->json()['predictions'];

        // $sorted = collect($predictions)->sortDesc();

        // dd($sorted->all());

        // $predictions = 
        
        $this->predictAllStudentPanels("PSM1");

        // return response()->json($predictions);

        // dd($predictions);

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
