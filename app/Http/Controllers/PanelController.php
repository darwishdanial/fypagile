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

    public function __construct(StudentService $studentService, PanelService $panelService, CoordinatorService $coordinatorService){

        $this->studentService = $studentService;
        $this->panelService = $panelService;
        $this->coordinatorService = $coordinatorService;
    }

    public function index()
    {
        $this->authorize('view panel dashboard');

        $dashboardData = $this->panelService->getPanelDashboardData();

        return Inertia::render('Panel/Home/Index', $dashboardData);
    }

    //PSM1

    public function PSM1ListPanels(){

        $this->authorize('view psm1 list panels table');

        $panelActive = $this->panelService->getPanelPSM1();

        $panelArchive = $this->panelService->getPanelPSM1Archive();

        return Inertia::render('Coordinator/PSM1/ListPanels',[
            'panels' => $panelActive,
            'archivedPanels' => $panelArchive
        ]);

    }

    public function PSM1autoAssignPanelsToStudents(){

        $user = Auth::user();
        $email = $user->email;
        $psmType = 'PSM1';
        $this->coordinatorService->autoAssignPanelsToStudents($psmType, $email);

        return redirect()->back()->with(['success' => 'AI Panel assignment process has started....']);

    }

    public function PSM1ArchivePanel($id){

        $this->panelService->archivePanel($id, 'PSM1');

        return redirect()->back()->with('success', 'Panel archived successfully.');
    }

    public function PSM1RestorePanel($id){

        $this->panelService->restorePanel($id, 'PSM1');

        return back()->with('success', 'Panel restore successfully.');
    }

    public function PSM1StorePanel(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer',
            'matricNo' => 'required|string|unique:users,matricNo|max:50',
            'email' => 'required|unique:users,email|max:255',
            'username' => 'required|string|unique:users,username|max:100',
            'password' => 'required|string|max:255',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'isArchivePSM1' => 'required|boolean',
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
            'isArchivePSM2' => 'required|boolean',
        ]);

        $this->panelService->createPanel($validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }

    public function PSM1UpdatePanel(Request $request, $id){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|integer',
            'matricNo' => 'required|string|max:50|unique:users,matricNo,'. $id,
            'email' => 'required|max:255|unique:users,email,'. $id,
            'username' => 'required|string|max:100|unique:users,username,'. $id,
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'password' => 'nullable|string|max:255',
        ]);        

        $this->panelService->updatePanel($id, $validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }

    public function PSM1DeletePanel($id){

        $this->panelService->deletePanel($id);

        return redirect()->back()->with('success', 'Panel deleted successfully.');
    }

    public function PSM1BulkArchivePanel(Request $request){

        $this->panelService->bulkArchivePanel($request->ids, 'PSM1');

        return redirect()->back()->with('success', 'Selected panels have been archived successfully!');
    }

    public function getPanelSample(){

        $filePath = $this->panelService->getPanelSample();
        
        if (!$filePath) {
            abort(404);
        }
    
        return response()->download($filePath);
    }

    public function ImportPanels(Request $request){

        $result = $this->panelService->importPanels($request->file('file'));

        if (is_array($result)) {
            return redirect()->back()->with('warning', $result);
        }

        return redirect()->back()->with('success', 'Panels imported successfully!');

    }

    //PSM2

    public function PSM2ListPanels()
    {
        $this->authorize('view psm2 list panels table');

        $panelActive = $this->panelService->getPanelPSM2();

        $panelArchive = $this->panelService->getPanelPSM2Archive();

        return Inertia::render('Coordinator/PSM2/ListPanels',[
            'panels' => $panelActive,
            'archivedPanels' => $panelArchive
        ]);
    }

    public function PSM2ArchivePanel($id){

        $this->panelService->archivePanel($id, 'PSM2');

        return redirect()->back()->with('success', 'Panel archived successfully.');
    }

    public function PSM2RestorePanel($id){

        $this->panelService->restorePanel($id, 'PSM2');

        return back()->with('success', 'Panel restore successfully.');
    }

    public function PSM2DeletePanel($id){

        $this->panelService->deletePanel($id);

        return redirect()->back()->with('success', 'Panel deleted successfully.');
    }

    public function PSM2BulkArchivePanel(Request $request){

        $this->panelService->bulkArchivePanel($request->ids, 'PSM2');

        return redirect()->back()->with('success', 'Selected panels have been archived successfully!');
    }

    public function PSM2StorePanel(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricNo' => 'required|string|unique:users,matricNo|max:50',
            'email' => 'required|unique:users,email|max:255',
            'username' => 'required|string|unique:users,username|max:100',
            'password' => 'required|string|max:255',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'isArchivePSM1' => 'required|boolean',
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
            'isArchivePSM2' => 'required|boolean',
            'role' => 'required',
        ]);

        $this->panelService->createPanel($validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }

    public function PSM2UpdatePanel(Request $request, $id){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricNo' => 'required|string|max:50|unique:users,matricNo,'. $id,
            'email' => 'required|max:255|unique:users,email,'. $id,
            'username' => 'required|string|max:100|unique:users,username,'. $id,
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
            'password' => 'nullable|string|max:255',
        ]);        

        $this->panelService->updatePanel($id, $validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }

}
