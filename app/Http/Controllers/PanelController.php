<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\User;
use Session;
use Exception;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\PanelService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Rubric;
use App\Models\Criteria;
use App\Models\Score;
use App\Services\CoordinatorService;
use Illuminate\Support\Facades\Hash;
use App\Imports\PanelsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;

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

        $userName = Auth::user()->name;
        $userId = Auth::user()->id;

        $studentsPSM1 = StudentPSM1:: where("supervisorId",$userId)->count();
        $studentsPSM2 = StudentPSM2:: where("supervisorId",$userId)->count();
        $panelsPSM1 = StudentPSM1:: where("panelId",$userId)
                                ->orWhere("panel2Id",$userId)
                                ->count();
        $panelsPSM2 = StudentPSM2:: where("panelId",$userId)
                                ->orWhere("panel2Id",$userId)
                                ->count();
        

        // dd($panelsPSM1);

        return Inertia::render('Panel/Home/Index',[
            'userName' => $userName,
            'studentsPSM1' => $studentsPSM1,
            'studentsPSM2' => $studentsPSM2,
            'panelsPSM1' => $panelsPSM1,
            'panelsPSM2' => $panelsPSM2
        ]);
    }

    //PSM1

    public function PSM1ListPanels()
    {
        $this->authorize('view psm1 list panels table');

        $panelActive = $this->panelService->getPanelPSM1();

        $panelArchive = $this->panelService->getPanelPSM1Archive();

        //dd($panelActive);

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

    public function PSM1ArchivePanel($id)
    {
        $panel = User ::findOrFail($id);

        //dd($panel);

        $panel->update([
            'isArchivePSM1' => 1,
            'isSupervisorPSM1' => 0,
            'isPanelPSM1' => 0,
        ]);

        return redirect()->back()->with('success', 'Panel archived successfully.');
    }

    public function PSM1RestorePanel($id)
    {
        $panel = User ::findOrFail($id);

        $panel->update([
            'isArchivePSM1' => 0,
        ]);

        return back()->with('success', 'Panel restore successfully.');
    }

    public function PSM1StorePanel(Request $request)
    {
        //dd( $request->all());

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

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }

    public function PSM1UpdatePanel(Request $request, $id)
    {
        //dd( $request->all());

        $panel = User::findOrFail($id);

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

        if (!$request->filled('password')) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $panel->update($validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }

    public function PSM1DeletePanel($id)
    {
        $panel = User::findOrFail($id);
        $panel->forceDelete(); 
        return redirect()->back()->with('success', 'Panel deleted successfully.');
    }

    public function PSM1BulkArchivePanel(Request $request){

        User::whereIn('id', $request->ids)->update([
            'isArchivePSM1' => 1,
            'isSupervisorPSM1' => 0,
            'isPanelPSM1' => 0,
        ]);

        return redirect()->back()->with('success', 'Selected panels have been archived successfully!');
    }

    public function ImportPanels(Request $request)
    {
        $import = new PanelsImport();
        Excel::import($import, $request->file('file'));

        $failures = Cache::get('panels_import_failures', []);

        if($failures){
            Cache::forget('panels_import_failures');
            //dd($failures);
            return redirect()->back()->with('warning', $failures);
        }

        return redirect()->back()->with('success', 'Panels imported successfully!');
    }

    //PSM2

    public function PSM2ListPanels()
    {
        $this->authorize('view psm2 list panels table');

        $panelActive = $this->panelService->getPanelPSM2();

        $panelArchive = $this->panelService->getPanelPSM2Archive();

        //dd($panelActive);

        return Inertia::render('Coordinator/PSM2/ListPanels',[
            'panels' => $panelActive,
            'archivedPanels' => $panelArchive
        ]);
    }

    public function PSM2ArchivePanel($id)
    {
        $panel = User ::findOrFail($id);

        //dd($panel);

        $panel->update([
            'isArchivePSM2' => 1,
            'isSupervisorPSM2' => 0,
            'isPanelPSM2' => 0,
        ]);

        return redirect()->back()->with('success', 'Panel archived successfully.');
    }

    public function PSM2RestorePanel($id)
    {
        $panel = User ::findOrFail($id);

        $panel->update([
            'isArchivePSM2' => 0,
        ]);

        return back()->with('success', 'Panel restore successfully.');
    }

    public function PSM2DeletePanel($id)
    {
        $panel = User::findOrFail($id);
        $panel->forceDelete(); 
        return redirect()->back()->with('success', 'Panel deleted successfully.');
    }

    public function PSM2BulkArchivePanel(Request $request){

        User::whereIn('id', $request->ids)->update([
            'isArchivePSM2' => 1,
            'isSupervisorPSM2' => 0,
            'isPanelPSM2' => 0,
        ]);

        return redirect()->back()->with('success', 'Selected panels have been archived successfully!');
    }

    public function PSM2StorePanel(Request $request)
    {
        //dd( $request->all());

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

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }

    public function PSM2UpdatePanel(Request $request, $id)
    {
        //dd( $request->all());

        $panel = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricNo' => 'required|string|max:50|unique:users,matricNo,'. $id,
            'email' => 'required|max:255|unique:users,email,'. $id,
            'username' => 'required|string|max:100|unique:users,username,'. $id,
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
            'password' => 'nullable|string|max:255',
        ]);        

        if (!$request->filled('password')) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $panel->update($validated);

        return redirect()->back()->with('success', 'Panel added successfully!');
    }


    

    //PSM1

    public function PSM1GradeSupervision(){

        $this->authorize('view psm1 grade supervision table');

        $id = Auth::user()->id;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGradePSM1($id, 1);

        // dd($studentsDevelopment);

        $studentResearch = $this->studentService->getStudentsSupervisorGradePSM1($id, 2);

        // dd($studentResearch);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('isDevelopment',  true)
            ->where('isEnable',  true)
            ->get();

            // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('isResearch',  true)
            ->where('isEnable',  true)
            ->get();    

            // dd($rubricsResearch);

        return Inertia::render('Panel/PSM1/GradeSupervision',[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);

    }

    public function PSM1Grade(){

        $this->authorize('view psm1 grade table');
        
        $id = Auth::user()->id;

        $studentsDevelopment = $this->studentService->getStudentsPanelGradePSM1($id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGradePSM1($id, 2);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('isDevelopment',  true)
            ->where('isEnable',  true)
            ->get();

        // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('isResearch',  true)
            ->where('isEnable',  true)
            ->get();    

        return Inertia::render('Panel/PSM1/GradePSM1',[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM1StoreScore(Request $request){

        //dd('panel id: '.$request->panel_id);

        $totalScore = array_sum($request->criteria);

        $weight = $request->total_weight;

        $finalScore = $weight/100 * $totalScore;

        $panelName = User::findOrFail($request->panel_id)->name;

        // dd($panelName);

        Score::updateOrCreate(
            [
                'rubric_id' => $request->rubric_id,
                'student_psm1_id' => $request->student_id,
                'panel_id' => $request->panel_id,
            ],
            [
                'mark' => $finalScore,
                'comment' => $request->comments,
                'panel_name' => $panelName,
            ]
        );

        return redirect()->back()->with('success', 'Score successfully stored.');

    }

    //PSM2

    public function PSM2GradeSupervision(){

        $this->authorize('view psm2 grade supervision table');

        $id = Auth::user()->id;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGradePSM2($id, 1);

        // dd($studentsDevelopment);

        $studentResearch = $this->studentService->getStudentsSupervisorGradePSM2($id, 2);

        // dd($studentResearch);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('isDevelopment',  true)
            ->where('isEnable',  true)
            ->get();

            // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('isResearch',  true)
            ->where('isEnable',  true)
            ->get();    

            // dd($rubricsResearch);

        return Inertia::render('Panel/PSM2/GradeSupervision',[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM2Grade(){

        $this->authorize('view psm2 grade table');

        $id = Auth::user()->id;

        $studentsDevelopment = $this->studentService->getStudentsPanelGradePSM1($id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGradePSM1($id, 2);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('isDevelopment',  true)
            ->where('isEnable',  true)
            ->get();

        // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('isResearch',  true)
            ->where('isEnable',  true)
            ->get();    

        return Inertia::render('Panel/PSM2/GradePSM2',[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM2StoreScore(Request $request){

        //dd('panel id: '.$request->panel_id);

        $totalScore = array_sum($request->criteria);

        $weight = $request->total_weight;

        $finalScore = $weight/100 * $totalScore;

        $panelName = User::findOrFail($request->panel_id)->name;

        // dd($panelName);

        Score::updateOrCreate(
            [
                'rubric_id' => $request->rubric_id,
                'student_psm2_id' => $request->student_id,
                'panel_id' => $request->panel_id,
            ],
            [
                'mark' => $finalScore,
                'comment' => $request->comments,
                'panel_name' => $panelName,
            ]
        );

        return redirect()->back()->with('success', 'Score successfully stored.');
    }

}
