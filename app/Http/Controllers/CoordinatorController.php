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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AiDataExport;

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

    // public function PSM1dashboard()
    // {
    //     // $this->authorize('view psm1 dashboard');

    //     // Get all PSM1 students
    //     $studentsPSM1 = $this->studentService->getStudents("PSM1");
    //     $totalStudents = $studentsPSM1->count();

    //     // Number of development students (assuming 'project_type' field)
    //     $developmentStudents = $studentsPSM1->where('project_type', 'System Development')->count();

    //     // Number of research students (assuming 'project_type' field)
    //     $researchStudents = $studentsPSM1->where('project_type', 'Research Based')->count();

    //     // Number of active PSM1 panels
    //     $activePanels = $this->panelService->getPanelPSM1()->count();

    //     $activeSupervisors = $this->supervisorService->getSupervisorPSM1()->count();

    //     // Students assigned to panel (assuming 'panel_name' and 'panel2_name' fields)
    //     $assignedToPanel = $studentsPSM1->whereNotNull('panel_name')
    //                                     ->whereNotNull('panel2_name')
    //                                     ->count();

    //     $panelAssignedPercentage = $totalStudents > 0 ? round(($assignedToPanel / $totalStudents) * 100, 2) : 0;

    //     // Students assigned to supervisor (assuming 'sv_name' field)
    //     $assignedToSupervisor = $studentsPSM1->whereNotNull('sv_name')->count();
    //     $supervisorAssignedPercentage = $totalStudents > 0 ? round(($assignedToSupervisor / $totalStudents) * 100, 2) : 0;

    //     // Count students for each project_area_ai
    //     $projectAreaAICounts = $studentsPSM1->groupBy('project_area_ai')->map(function($group) {
    //         return $group->count();
    //     });

    //     return Inertia::render('Coordinator/Home/DashboardPSM1', [
    //         'totalStudents' => $totalStudents,
    //         'developmentStudents' => $developmentStudents,
    //         'researchStudents' => $researchStudents,
    //         'activePanels' => $activePanels,
    //         'activeSupervisors' => $activeSupervisors,

    //         'panelAssignedNumber' => $assignedToPanel,
    //         'panelAssignedTotal' => $totalStudents,
    //         'panelAssignedPercentage' => $panelAssignedPercentage,

    //         'supervisorAssignedNumber' => $assignedToSupervisor,
    //         'supervisorAssignedTotal' => $totalStudents,
    //         'supervisorAssignedPercentage' => $supervisorAssignedPercentage,

    //         'projectAreaAICounts' => $projectAreaAICounts,
    //     ]);
    // }

    public function Dashboard()
    {
        // $this->authorize('view psm1 dashboard');

        // --- PSM1 Data ---
        $studentsPSM1 = $this->studentService->getStudents("PSM1");
        $totalStudentsPSM1 = $studentsPSM1->count();
        $developmentStudentsPSM1 = $studentsPSM1->where('project_type', 'System Development')->count();
        $researchStudentsPSM1 = $studentsPSM1->where('project_type', 'Research Based')->count();
        $activePanelsPSM1 = $this->panelService->getPanelPSM1()->count();
        $activeSupervisorsPSM1 = $this->supervisorService->getSupervisorPSM1()->count();
        $assignedToPanelPSM1 = $studentsPSM1->whereNotNull('panel_name')
                                            ->whereNotNull('panel2_name')
                                            ->count();
        $panelAssignedPercentagePSM1 = $totalStudentsPSM1 > 0 ? round(($assignedToPanelPSM1 / $totalStudentsPSM1) * 100, 2) : 0;
        $assignedToSupervisorPSM1 = $studentsPSM1->whereNotNull('sv_name')->count();
        $supervisorAssignedPercentagePSM1 = $totalStudentsPSM1 > 0 ? round(($assignedToSupervisorPSM1 / $totalStudentsPSM1) * 100, 2) : 0;
        $projectAreaAICountsPSM1 = $studentsPSM1->groupBy('project_area_ai')->map(function($group) {
            return $group->count();
        });

        // --- PSM2 Data ---
        $studentsPSM2 = $this->studentService->getStudents("PSM2");
        $totalStudentsPSM2 = $studentsPSM2->count();
        $developmentStudentsPSM2 = $studentsPSM2->where('project_type', 'System Development')->count();
        $researchStudentsPSM2 = $studentsPSM2->where('project_type', 'Research Based')->count();
        $activePanelsPSM2 = $this->panelService->getPanelPSM2()->count();
        $activeSupervisorsPSM2 = $this->supervisorService->getSupervisorPSM2()->count();
        $assignedToPanelPSM2 = $studentsPSM2->whereNotNull('panel_name')
                                            ->whereNotNull('panel2_name')
                                            ->count();
        $panelAssignedPercentagePSM2 = $totalStudentsPSM2 > 0 ? round(($assignedToPanelPSM2 / $totalStudentsPSM2) * 100, 2) : 0;
        $assignedToSupervisorPSM2 = $studentsPSM2->whereNotNull('sv_name')->count();
        $supervisorAssignedPercentagePSM2 = $totalStudentsPSM2 > 0 ? round(($assignedToSupervisorPSM2 / $totalStudentsPSM2) * 100, 2) : 0;
        $projectAreaAICountsPSM2 = $studentsPSM2->groupBy('project_area_ai')->map(function($group) {
            return $group->count();
        });

        return Inertia::render('Coordinator/Home/Dashboard', [
            'psm1' => [
                'totalStudents' => $totalStudentsPSM1,
                'developmentStudents' => $developmentStudentsPSM1,
                'researchStudents' => $researchStudentsPSM1,
                'activePanels' => $activePanelsPSM1,
                'activeSupervisors' => $activeSupervisorsPSM1,
                'panelAssignedNumber' => $assignedToPanelPSM1,
                'panelAssignedTotal' => $totalStudentsPSM1,
                'panelAssignedPercentage' => $panelAssignedPercentagePSM1,
                'supervisorAssignedNumber' => $assignedToSupervisorPSM1,
                'supervisorAssignedTotal' => $totalStudentsPSM1,
                'supervisorAssignedPercentage' => $supervisorAssignedPercentagePSM1,
                'projectAreaAICounts' => $projectAreaAICountsPSM1,
            ],
            'psm2' => [
                'totalStudents' => $totalStudentsPSM2,
                'developmentStudents' => $developmentStudentsPSM2,
                'researchStudents' => $researchStudentsPSM2,
                'activePanels' => $activePanelsPSM2,
                'activeSupervisors' => $activeSupervisorsPSM2,
                'panelAssignedNumber' => $assignedToPanelPSM2,
                'panelAssignedTotal' => $totalStudentsPSM2,
                'panelAssignedPercentage' => $panelAssignedPercentagePSM2,
                'supervisorAssignedNumber' => $assignedToSupervisorPSM2,
                'supervisorAssignedTotal' => $totalStudentsPSM2,
                'supervisorAssignedPercentage' => $supervisorAssignedPercentagePSM2,
                'projectAreaAICounts' => $projectAreaAICountsPSM2,
                ],
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
        $this->authorize('request psm1 supervisor');

        $studentId = $request->input('studentId');
        $supervisorId = $request->input('supervisorId');

        $this->studentService->requestStudentsSupervisor("PSM1", $studentId, $supervisorId);
    }

    public function PSM1CancelRequestSupervisor($studentId)
    {
        $this->authorize('cancel request psm1 supervisor');

        $this->studentService->cancelRequestStudentsSupervisor("PSM1", $studentId);
    }

    public function PSM2RequestSupervisor(Request $request)
    {
        $this->authorize('request psm2 supervisor');

        $studentId = $request->input('studentId');
        $supervisorId = $request->input('supervisorId');

        $this->studentService->requestStudentsSupervisor("PSM2", $studentId, $supervisorId);
    }

    public function PSM2CancelRequestSupervisor($studentId)
    {
        $this->authorize('cancel request psm2 supervisor');

        $this->studentService->cancelRequestStudentsSupervisor("PSM2", $studentId);
    }

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

        // dd($mergerService->mergePanelAndProjectData());

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

        

        // $this->coordinatorService->exportAiData();

        try {
            return Excel::download(new AiDataExport, 'ai_data.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getSampleData(ProjectLecturerMergerService $mergerService)
    {
        $this->authorize('view sample data');

        $mergerService->mergePanelAndProjectDataWithMapping(true);
    }
}
