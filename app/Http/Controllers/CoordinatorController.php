<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ProjectLecturerMergerService;
use App\Services\CoordinatorService;
use App\Services\StudentService;
use App\Services\PanelService;
use App\Services\SupervisorService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


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

    // public function PSM1autoAssignPanelsToStudents(){

    //     $user = Auth::user();
    //     $email = $user->email;
    //     $psmType = 'PSM1';
    //     $this->coordinatorService->autoAssignPanelsToStudents($psmType, $email);

    //     return redirect()->back()->with(['success' => 'AI Panel assignment process has started....']);

    // }

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

    public function PSM1PredictPanel(){

        $this->coordinatorService->predictAllStudentPanels("PSM1");

    }

    public function PSM2PredictPanel(){
        
        $this->coordinatorService->predictAllStudentPanels("PSM2");
    }

    public function removeAllPanelIdsFromPSM1()
    {
        $this->coordinatorService->removeAllPanelIdsFromPSM1();
    }

    public function removeAllPanelIdsFromPSM2()
    {
        $this->coordinatorService->removeAllPanelIdsFromPSM2();
    }

    public function getMLData(ProjectLecturerMergerService $mergerService){

        $projectArea = $mergerService->mergePanelAndProjectDataWithMapping(true);

        return Inertia::render('Coordinator/Home/MLData',[
            'totalPanel' => $projectArea['panelCount'],
            'totalProjectArea' =>$projectArea['projectAreaCount'],
            'totalProjectType' => $projectArea['projectTypeCount'],
            'totalSamples' =>  count($projectArea['samples']),
            'totalLabels' => count($projectArea['labels']),
            'asgCountPerPanel' => $projectArea['panelAssignments'],
        ]);

    }

    public function getPanelHistory(){

        $users = $this->coordinatorService->getPanelHistory();

        return Inertia::render('Coordinator/Home/PanelHistory', [
            'users' => $users
        ]);

    }

    public function exportAiData()
    {
        $this->coordinatorService->exportAiData();
    }

    public function getSampleData(ProjectLecturerMergerService $mergerService){

        $mergerService->mergePanelAndProjectDataWithMapping(true);
    }

    // public function PSM1PredictPanel(){

    //     $this->coordinatorService->predictAllStudentPanels("PSM1");

        // $response = Http::timeout(5)->post('http://127.0.0.1:8001/predict-panel', [
        //     'project_area' => 3,
        //     'project_type' => 1,
        // ]);
        
        // $predictions = $response->json()['predictions'];

        // $sorted = collect($predictions)->sortDesc();

        // dd($sorted->all());

        // $predictions = 

        // return response()->json($predictions);

        // dd($predictions);

    // }


    
    

}
