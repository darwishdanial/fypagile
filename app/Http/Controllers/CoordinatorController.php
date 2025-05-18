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

    public function __construct(CoordinatorService $coordinatorService, StudentService $studentService, PanelService $panelService, SupervisorService $supervisorService)
    {
        $this->coordinatorService = $coordinatorService;
        $this->studentService = $studentService;
        $this->panelService = $panelService;
        $this->supervisorService = $supervisorService;
    }

    // Home
    public function index()
    {
        $this->authorize('view coordinator dashboard');

        $userName = Auth::user()->name;
        $studentsPSM1 = $this->studentService->getStudents("PSM1")->count();
        $studentsPSM2 = $this->studentService->getStudents("PSM2")->count();
        $panelsPSM1 = $this->panelService->getPanelPSM1()->count();
        $panelsPSM2 = $this->panelService->getPanelPSM2()->count();

        return Inertia::render('Coordinator/Home/Index', [
            'userName' => $userName,
            'studentsPSM1' => $studentsPSM1,
            'studentsPSM2' => $studentsPSM2,
            'panelsPSM1' => $panelsPSM1,
            'panelsPSM2' => $panelsPSM2
        ]);
    }

    // Supervisor Assign - PSM1
    public function PSM1ListSupervisor()
    {
        $this->authorize('view psm1 assign supervisor table');

        $supervisors = $this->supervisorService->getSupervisorPSM1();

        return Inertia::render('Coordinator/PSM1/AssignSupervisor', [
            'supervisor' => $supervisors
        ]);
    }

    public function PSM1SupervisorStudentList($id)
    {
        $this->authorize('view psm1 supervisor student list');

        $students = $this->studentService->getStudentsSupervisor("PSM1", $id);

        return $students;
    }

    public function PSM1RequestSupervisor(Request $request)
    {
        $this->authorize('assign psm1 supervisor');

        $studentId = $request->input('studentId');
        $supervisorId = $request->input('supervisorId');

        $this->studentService->requestStudentsSupervisor("PSM1", $studentId, $supervisorId);
    }

    public function PSM1CancelRequestSupervisor($studentId)
    {
        $this->authorize('assign psm1 supervisor');

        $this->studentService->cancelRequestStudentsSupervisor("PSM1", $studentId);
    }

    public function PSM2RequestSupervisor(Request $request)
    {
        // $this->authorize('assign psm1 supervisor');

        $studentId = $request->input('studentId');
        $supervisorId = $request->input('supervisorId');

        $this->studentService->requestStudentsSupervisor("PSM2", $studentId, $supervisorId);
    }

    public function PSM2CancelRequestSupervisor($studentId)
    {
        // $this->authorize('assign psm1 supervisor');

        $this->studentService->cancelRequestStudentsSupervisor("PSM2", $studentId);
    }

    // public function PSM1SAcceptSupervisor(Request $request)
    // {
    //     $this->authorize('assign psm1 supervisor');

    //     $studentId = $request->input('studentId');
    //     $supervisorId = $request->input('supervisorId');

    //     $this->studentService->assignStudentsSupervisor("PSM1", $studentId, $supervisorId);
    // }

    public function PSM1UnassignSupervisor($studentId)
    {
        $this->authorize('unassign psm1 supervisor');

        $this->studentService->unassignStudentsSupervisor("PSM1", $studentId);
    }

    // Supervisor Assign - PSM2
    public function PSM2ListSupervisor()
    {
        $this->authorize('view psm2 assign supervisor table');

        $supervisors = $this->supervisorService->getSupervisorPSM2();

        return Inertia::render('Coordinator/PSM2/AssignSupervisorPSM2', [
            'supervisor' => $supervisors
        ]);
    }

    public function PSM2SupervisorStudentList($id)
    {
        $this->authorize('view psm2 supervisor student list');

        $students = $this->studentService->getStudentsSupervisor("PSM2", $id);

        return $students;
    }

    // public function PSM2SAssignSupervisor(Request $request)
    // {
    //     $this->authorize('assign psm2 supervisor');

    //     $studentId = $request->input('studentId');
    //     $supervisorId = $request->input('supervisorId');

    //     $this->studentService->assignStudentsSupervisor("PSM2", $studentId, $supervisorId);
    // }

    public function PSM2UnassignSupervisor($studentId)
    {
        $this->authorize('unassign psm2 supervisor');

        $this->studentService->unassignStudentsSupervisor("PSM2", $studentId);
    }

    // Panel Assign - PSM1
    public function PSM1listAssignPanel()
    {
        $this->authorize('view psm1 assign panel table');

        $panels = $this->panelService->getAssignPanelPSM1();

        return Inertia::render('Coordinator/PSM1/AssignPSM1Panel', [
            'panel' => $panels
        ]);
    }

    public function PSM1PanelStudentList(Request $request)
    {
        $this->authorize('view psm1 panel student list');

        $panelId = $request->input('panelId');
        $type = $request->input('panelTypeValue');

        $students = $this->studentService->getStudentsPanel("PSM1", $panelId, $type);

        return $students;
    }

    public function PSM1SAssignPanel1(Request $request)
    {
        $this->authorize('assign psm1 panel 1');

        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');

        $this->studentService->assignStudentsPanel1("PSM1", $studentId, $panelId);
    }

    public function PSM1SAssignPanel2(Request $request)
    {
        $this->authorize('assign psm1 panel 2');

        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');

        $this->studentService->assignStudentsPanel2("PSM1", $studentId, $panelId);
    }

    public function PSM1UnassignPSMPanel1($studentId)
    {
        $this->authorize('unassign psm1 panel 1');

        $this->studentService->unassignStudentsPanel1("PSM1", $studentId);
    }

    public function PSM1UnassignPSMPanel2($studentId)
    {
        $this->authorize('unassign psm1 panel 2');

        $this->studentService->unassignStudentsPanel2("PSM1", $studentId);
    }

    // Panel Assign - PSM2
    public function PSM2ListPanel()
    {
        $this->authorize('view psm2 assign panel table');

        $panels = $this->panelService->getAssignPanelPSM2();

        return Inertia::render('Coordinator/PSM2/AssignPSM2Panel', [
            'panel' => $panels
        ]);
    }

    public function PSM2PanelStudentList(Request $request)
    {
        $this->authorize('view psm2 panel student list');

        $panelId = $request->input('panelId');
        $type = $request->input('panelTypeValue');

        $students = $this->studentService->getStudentsPanel("PSM2", $panelId, $type);

        return $students;
    }

    public function PSM2SAssignPanel1(Request $request)
    {
        $this->authorize('assign psm2 panel 1');

        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');

        $this->studentService->assignStudentsPanel1("PSM2", $studentId, $panelId);
    }

    public function PSM2SAssignPanel2(Request $request)
    {
        $this->authorize('assign psm2 panel 2');

        $studentId = $request->input('studentId');
        $panelId = $request->input('panelId');

        $this->studentService->assignStudentsPanel2("PSM2", $studentId, $panelId);
    }

    public function PSM2UnassignPSMPanel1($studentId)
    {
        $this->authorize('unassign psm2 panel 1');

        $this->studentService->unassignStudentsPanel1("PSM2", $studentId);
    }

    public function PSM2UnassignPSMPanel2($studentId)
    {
        $this->authorize('unassign psm2 panel 2');

        $this->studentService->unassignStudentsPanel2("PSM2", $studentId);
    }

    // AI
    public function PSM1PredictPanel()
    {
        $this->authorize('predict psm1 panel');

        return $this->coordinatorService->predictAllStudentPanels("PSM1");
    }

    public function PSM2PredictPanel()
    {
        $this->authorize('predict psm2 panel');

        return $this->coordinatorService->predictAllStudentPanels("PSM2");
    }

    public function removeAllPanelIdsFromPSM1()
    {
        $this->authorize('remove all psm1 panel ids');

        return $this->coordinatorService->removeAllPanelIds("PSM1");
    }

    public function removeAllPanelIdsFromPSM2()
    {
        $this->authorize('remove all psm2 panel ids');

        return $this->coordinatorService->removeAllPanelIds("PSM2");
    }

    public function getMLData(ProjectLecturerMergerService $mergerService)
    {
        $this->authorize('view ml data');

        $projectArea = $mergerService->mergePanelAndProjectDataWithMapping(true);

        return Inertia::render('Coordinator/Home/MLData', [
            'totalPanel' => $projectArea['panelCount'],
            'totalProjectArea' => $projectArea['projectAreaCount'],
            'totalProjectType' => $projectArea['projectTypeCount'],
            'totalSamples' => count($projectArea['samples']),
            'totalLabels' => count($projectArea['labels']),
            'asgCountPerPanel' => $projectArea['panelAssignments'],
        ]);
    }

    public function getPanelHistory()
    {
        $this->authorize('view panel history');

        $users = $this->coordinatorService->getPanelHistory();

        return Inertia::render('Coordinator/Home/PanelHistory', [
            'users' => $users
        ]);
    }

    public function exportAiData()
    {
        $this->authorize('export ai data');

        $this->coordinatorService->exportAiData();
    }

    public function getSampleData(ProjectLecturerMergerService $mergerService)
    {
        $this->authorize('view sample data');

        $mergerService->mergePanelAndProjectDataWithMapping(true);
    }
}
