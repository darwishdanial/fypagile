<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\PanelService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\CoordinatorService;

class PanelController extends Controller
{
    protected $studentService;
    protected $panelService;
    protected $coordinatorService;

    public function __construct(StudentService $studentService, PanelService $panelService, CoordinatorService $coordinatorService)
    {
        $this->studentService = $studentService;
        $this->panelService = $panelService;
        $this->coordinatorService = $coordinatorService;
    }

    public function index()
    {
        $this->authorize('view panel dashboard');

        $dashboardData = $this->panelService->getPanelDashboardData();

        return Inertia::render('Panel/Home/index', $dashboardData);
    }

    // PSM1

    public function PSM1ListPanels()
    {
        $this->authorize('view psm1 list panels table');

        $panelActive = $this->panelService->getPanelPSM1();
        $panelArchive = $this->panelService->getPanelPSM1Archive();

        return Inertia::render('Coordinator/PSM1/ListPanels', [
            'panels' => $panelActive,
            'archivedPanels' => $panelArchive,
            'currenrtUser' => Auth::user()->id,
        ]);
    }

    // public function PSM1autoAssignPanelsToStudents()
    // {
    //     $this->authorize('auto assign psm1 panels');

    //     $user = Auth::user();
    //     $email = $user->email;
    //     $psmType = 'PSM1';
    //     $this->coordinatorService->autoAssignPanelsToStudents($psmType, $email);

    //     return redirect()->back()->with(['success' => 'AI Panel assignment process has started....']);
    // }

    public function PSM1ArchivePanel($id)
    {
        $this->authorize('archive psm1 panels');

        return $this->panelService->archivePanel($id, 'PSM1');
    }

    public function PSM1RestorePanel($id)
    {
        $this->authorize('restore psm1 panels');

        return $this->panelService->restorePanel($id, 'PSM1');
    }

    public function PSM1StorePanel(Request $request)
    {
        $this->authorize('store psm1 panels');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer',
            'matricNo' => 'required|string|unique:users,matricNo|max:50',
            'email' => 'required|unique:users,email|max:255',
            'username' => 'required|string|unique:users,username|max:100',
            'password' => 'required|max:255',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'isArchivePSM1' => 'required|boolean',
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
            'isArchivePSM2' => 'required|boolean',
        ]);

        return $this->panelService->createPanel($validated);
    }

    public function PSM1UpdatePanel(Request $request, $id)
    {
        $this->authorize('update psm1 panels');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer',
            'matricNo' => 'required|string|max:50|unique:users,matricNo,' . $id,
            'email' => 'required|max:255|unique:users,email,' . $id,
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'password' => 'nullable|max:255',
        ]);

        return $this->panelService->updatePanel($id, $validated);
    }

    public function PSM1DeletePanel($id)
    {
        $this->authorize('delete psm1 panels');

        return $this->panelService->deletePanel($id);
    }

    public function PSM1BulkArchivePanel(Request $request)
    {
        $this->authorize('bulk archive psm1 panels');

        return $this->panelService->bulkArchivePanel($request->ids, 'PSM1');
    }

    public function getPanelSample()
    {
        $this->authorize('download panel sample');

        $filePath = $this->panelService->getPanelSample();

        if (!$filePath) {
            abort(404);
        }

        return response()->download($filePath);
    }

    public function ImportPanels(Request $request)
    {
        $this->authorize('import panels');

        return $this->panelService->importPanels($request->file('file'));
    }

    // PSM2

    public function PSM2ListPanels()
    {
        $this->authorize('view psm2 list panels table');

        $panelActive = $this->panelService->getPanelPSM2();
        $panelArchive = $this->panelService->getPanelPSM2Archive();

        return Inertia::render('Coordinator/PSM2/ListPanels', [
            'panels' => $panelActive,
            'archivedPanels' => $panelArchive,
            'currenrtUser' => Auth::user()->id,
        ]);
    }

    public function PSM2ArchivePanel($id)
    {
        $this->authorize('archive psm2 panels');

        return $this->panelService->archivePanel($id, 'PSM2');
    }

    public function PSM2RestorePanel($id)
    {
        $this->authorize('restore psm2 panels');

        return $this->panelService->restorePanel($id, 'PSM2');
    }

    public function PSM2DeletePanel($id)
    {
        $this->authorize('delete psm2 panels');

        return $this->panelService->deletePanel($id);
    }

    public function PSM2BulkArchivePanel(Request $request)
    {
        $this->authorize('bulk archive psm2 panels');

        return $this->panelService->bulkArchivePanel($request->ids, 'PSM2');
    }

    public function PSM2StorePanel(Request $request)
    {
        $this->authorize('store psm2 panels');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricNo' => 'required|string|unique:users,matricNo|max:50',
            'email' => 'required|unique:users,email|max:255',
            'username' => 'required|string|unique:users,username|max:100',
            'password' => 'required|max:255',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'isArchivePSM1' => 'required|boolean',
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
            'isArchivePSM2' => 'required|boolean',
            'role' => 'required',
        ]);

        return $this->panelService->createPanel($validated);
    }

    public function PSM2UpdatePanel(Request $request, $id)
    {
        $this->authorize('update psm2 panels');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer',
            'matricNo' => 'required|string|max:50|unique:users,matricNo,' . $id,
            'email' => 'required|max:255|unique:users,email,' . $id,
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
            'password' => 'nullable|max:255',
        ]);

        return $this->panelService->updatePanel($id, $validated);
    }
}
