<?php

namespace App\Http\Controllers;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\User;
use App\Models\Rubric;
use App\Models\Criteria;
use App\Models\Score;
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
use Illuminate\Validation\Rule;
use App\Imports\PSM1StudentsImport;
use App\Imports\PSM2StudentsImport;
use App\Imports\PanelsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

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

    //PSM1
    //Student Management

    public function PSM1ListStudents()
    {
        $this->authorize('view psm1 list students table');

        $students = $this->studentService->getStudentPSM1();
        $archivedStudents = $this->studentService->getStudentPSM1Archive();

        return Inertia::render('Coordinator/PSM1/ListStudents',[
            'students' => $students,
            'archivedStudents' => $archivedStudents
        ]);
    }

    public function PSM1ArchiveStudent($id)
    {
        $student = StudentPSM1::findOrFail($id);
        $student->delete(); // Soft delete
        return redirect()->back()->with('success', 'Student archived successfully.');
    }

    public function PSM1RestoreStudent($id)
    {
        $student = StudentPSM1::onlyTrashed()->findOrFail($id);
        $student->restore(); // Restores the soft-deleted student
        return back()->with('success', 'Student restore successfully.');
    }

    public function PSM1DeleteStudent($id)
    {
        $student = StudentPSM1::onlyTrashed()->findOrFail($id);
        $student->forceDelete(); // Delete permanently
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    public function PSM1StoreStudent(Request $request)
    {
        // dd( $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|unique:students_psm1,matric|max:50',
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students_psm1,email|max:255',
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        StudentPSM1::create($validated);

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    public function PSM1UpdateStudent(Request $request, $id)
    {

        $student = StudentPSM1::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|max:50|unique:students_psm1,matric,' . $id,
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:students_psm1,email,' . $id,
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        $student->update([
            'name' => $request->name,
            'matric' => $request->matric,
            'course' => $request->course,
            'cohort' => $request->cohort,
            'phone' => $request->phone,
            'email' => $request->email,
            'project_type' => $request->project_type,
            'project_area' => $request->project_area,
            'title' => $request->title,
            'sessionpsm' => $request->sessionpsm,
        ]);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function PSM1ImportStudent(Request $request)
    {

        $import = new PSM1StudentsImport();
        Excel::import($import, $request->file('file'));

        $failures = Cache::get('psm1_import_failures', []);

        if($failures){
            Cache::forget('psm1_import_failures');
            //dd($failures);
            return redirect()->back()->with('warning', $failures);
        }

        return redirect()->back()->with('success', 'Student imported successfully!');
    }

    public function PSM1BulkArchiveStudent(Request $request){

        StudentPSM1::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', 'Selected students have been archived successfully!');
    }

    //Panel Management

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

    public function PSM1EvaluationRurbric()
    {
        $this->authorize('view psm1 evaluation rubric');

        $rubrics = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM1')
                    ->get();

        return Inertia::render('Coordinator/PSM1/EvaluationRubric',[
            'rubrics' => $rubrics
        ]);
    }

    public function PSM1StoreEvaluationRurbric(Request $request)
    {
        // dd( $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'total_weight' => 'required|integer|min:0|max:100',
            'isEnable' => 'required|boolean',
            'isCoordinatorPSM1' => 'required|boolean',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'isCoordinatorPSM2' => 'required|boolean',
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
        ]);

        Rubric::create($validated);

        return redirect()->back()->with('success', 'Rubric added successfully!');
    }

    public function PSM1StoreEvaluationCriteria(Request $request)
    {
        //dd( $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        Criteria::create($validated);

        return redirect()->back()->with('success', 'Criteria added successfully!');
    }

    public function PSM1UpdateEvaluationRurbric(Request $request, $id)
    {
        $rubric = Rubric::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'total_weight' => 'required|integer|min:0|max:100',
            'isEnable' => 'required|boolean',
            'isCoordinatorPSM1' => 'required|boolean',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
        ]);

        $rubric->update($validated);

        return redirect()->back()->with('success', 'Rubric updated successfully!');
    }


    public function PSM1UpdateEvaluationCriteria(Request $request, $id)
    {
        //dd( $request->all());

        $criteria = Criteria::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|number|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        $criteria->update($validated);

        return redirect()->back()->with('success', 'Criteria updated successfully!');
    }

    public function PSM1DeleteEvaluationRurbric($id)
    {
        $rubric = Rubric::findOrFail($id);
        $rubric->delete(); // Soft delete
        return redirect()->back()->with('success', 'Rubric deleted successfully.');
    }

    public function PSM1DeleteEvaluationCriteria($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete(); // Soft delete
        return redirect()->back()->with('success', 'Criteria deleted successfully.');
    }

    public function PSM1GradeSupervision()
    {
        $this->authorize('view psm1 grade supervision table');

        $id = Auth::user()->id;

        $students = $this->studentService->getStudentsSupervisorGradePSM1($id);

        $rubrics = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                ->where('PSMType',  'PSM1')
                ->where('isSupervisorPSM1',  true)
                ->get();

        return Inertia::render('Coordinator/PSM1/GradeSupervision',[
            'students' => $students,
            'rubrics' => $rubrics,
            'id' => $id
        ]);
    }

    public function PSM1GradePanel()
    {
        $this->authorize('view psm1 grade table');

        $id = Auth::user()->id;

        $students = $this->studentService->getStudentsPanelGradePSM1($id);

        $rubrics = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                ->where('PSMType',  'PSM1')
                ->where('isPanelPSM1',  true)
                ->get();

        return Inertia::render('Coordinator/PSM1/GradePSM1',[
            'students' => $students,
            'rubrics' => $rubrics,
            'id' => $id
        ]);
    }

    public function PSM1GradeCoordinator()
    {
        $id = Auth::user()->id;

        $students = StudentPSM1::all();

        $rubrics = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                ->where('PSMType',  'PSM1')
                ->where('isCoordinatorPSM1',  true)
                ->get();

        return Inertia::render('Coordinator/PSM1/GradePSM1Coordinator',[
            'students' => $students,
            'rubrics' => $rubrics,
            'id' => $id
        ]);
    }

    public function PSM1StoreScore(Request $request){

        // dd('panel id: '.$request->panel_id);

        $totalScore = array_sum($request->criteria);

        $weight = $request->total_weight;

        $finalScore = $weight/100 * $totalScore;

        $panelName = User::findOrFail($request->panel_id)->name;

        // dd($panelName);

        // Score::create([
        //     'rubric_id' => $request->rubric_id,
        //     'student_psm1_id' => $request->student_id,
        //     'mark' => $finalScore,
        //     'comment' => $request->comments,
        // ]);

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

        // dd($request->criteria,$request->student_id, $id, $request->comments,$request->total_weight, $totalScore, $finalScore, $request->rubric_id);
    }

    public function PSM1ViewResult()
    {
        $this->authorize('view psm1 result table');

        $students = StudentPSM1::with(['score.rubric'])->get();

        // dd($students[2]);

        $rubric = Rubric:: where('PSMType',  'PSM1') ->get();

        return Inertia::render('Coordinator/PSM1/ViewResult',[
            'students' => $students,
            'rubrics' => $rubric,
        ]);
    }




    //PSM2
    //Student Management

    public function PSM2ListStudents()
    {
        $this->authorize('view psm2 list students table');

        $students = $this->studentService->getStudentPSM2();
        $archivedStudents =$this->studentService->getStudentPSM2Archive();

        return Inertia::render('Coordinator/PSM2/ListStudents',[
            'students' => $students,
            'archivedStudents' => $archivedStudents
        ]);
    }

    public function PSM2ArchiveStudent($id)
    {
        $student = StudentPSM2::findOrFail($id);
        $student->delete(); // Soft delete
        return redirect()->back()->with('success', 'Student archived successfully.');
    }

    public function PSM2RestoreStudent($id)
    {
        $student = StudentPSM2::onlyTrashed()->findOrFail($id);
        $student->restore(); // Restores the soft-deleted student
        return back()->with('success', 'Student restore successfully.');
    }

    public function PSM2DeleteStudent($id)
    {
        $student = StudentPSM2::onlyTrashed()->findOrFail($id);
        $student->forceDelete(); // Delete permanently
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    public function PSM2StoreStudent(Request $request)
    {
        //dd( $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|unique:students_psm1,matric|max:50',
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students_psm1,email|max:255',
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        StudentPSM2::create($validated);

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    public function PSM2UpdateStudent(Request $request, $id)
    {

        $student = StudentPSM2::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|max:50|unique:students_psm1,matric,' . $id,
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|max:255|unique:students_psm1,email,' . $id,
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        $student->update([
            'name' => $request->name,
            'matric' => $request->matric,
            'course' => $request->course,
            'cohort' => $request->cohort,
            'phone' => $request->phone,
            'email' => $request->email,
            'project_type' => $request->project_type,
            'project_area' => $request->project_area,
            'title' => $request->title,
            'sessionpsm' => $request->sessionpsm,
        ]);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function PSM2ImportStudent(Request $request)
    {

        $import = new PSM2StudentsImport();
        Excel::import($import, $request->file('file'));

        $failures = Cache::get('psm2_import_failures', []);

        if($failures){
            Cache::forget('psm2_import_failures');
            //dd($failures);
            return redirect()->back()->with('warning', $failures);
        }

        return redirect()->back()->with('success', 'Student imported successfully!');
    }

    public function PSM2BulkArchive(Request $request){

        StudentPSM2::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', 'Selected students have been archived successfully!');
    }

    //Panel Management

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

    //panel 
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









    public function PSM2ViewResult()
    {
        $this->authorize('view psm2 result table');

        return Inertia::render('Coordinator/PSM2/ViewResult');
    }

    public function PSM2EvaluationRurbric()
    {
        $this->authorize('view psm2 evaluation rubric');

        return Inertia::render('Coordinator/PSM2/EvaluationRubric');
    }

    public function PSM2GradeSupervision()
    {
        $this->authorize('view psm2 grade supervision table');

        return Inertia::render('Coordinator/PSM2/GradeSupervision');
    }

    public function PSM2Grade()
    {
        $this->authorize('view psm2 grade table');

        return Inertia::render('Coordinator/PSM2/GradePSM2');
    }


    /////////////////////////////////////////////////////////////////////////////////



    public function rubicPSM1(){

        return view('PSM1.coordinator.rubricPage');

    }

    public function listPSM1(){

        $students = $this->studentService->getStudentPSM1();
      
        return view('PSM1.liststudent',compact('students'));
    }

    public function listPSM2(){

        $students = $this->studentService->getStudentPSM2();

        return view('PSM2.liststudent',compact('students'));
    }

    public function totalpsm(){

        return $this->coordinatorService->getTotalMarksPSM1();
    }

    public function listresultPSM1() {

        $totalResult = $this->coordinatorService->getResultPSM1();

        return view('PSM1.listresult', compact('totalResult'));
    }

    public function listresultPSM2() {
        
        $totalResult = $this->coordinatorService->getResultPSM2();

        return view('PSM2.listresult', compact('totalResult'));
    }

    public function editstudentPSM1($id){

        $data = $this->coordinatorService->editstudentPSM1($id);

        return view('PSM1.student.edit', $data);
    }

    public function editstudentPSM2($id){

        $data = $this->coordinatorService->editstudentPSM2($id);

        return view('PSM2.student.edit', $data);
    }

    public function updatestudentPSM1(Request $request, StudentPSM1 $student){

        $request->validate([
            'name' => 'required',
            'matric' => 'required',
        ]);

        $student->update($request->all());

        return redirect()->route('listPSM1')->with('success', 'Student has been updated successfully');
    }

    public function updatestudentPSM2(Request $request, StudentPSM2 $student){

        $request->validate([
            'name' => 'required',
            'matric' => 'required',
        ]);

        $student->update($request->all());

        return redirect()->route('listPSM2')->with('success', 'Student has been updated successfully');
    }

    public function destroy(StudentPSM1 $student){

        $student->delete();

        return redirect()->route('listPSM1')->with('success', 'Student has been deleted successfully');
    }


    public function viewresultPSM1($id){
        
        $data = $this->coordinatorService->viewResultPSM1($id);

        return view('PSM1.student.result', $data);
    }

    public function viewresultPSM2($id){

        $data = $this->coordinatorService->viewResultPSM2($id);

        return view('PSM2.student.result', $data);
    }

    public function cgrade(){

        $students = studentPSM1::get();

        return view('PSM1.coordinator.listpelajar',compact('students'));
    }

    public function cgrade2(){

        $students = studentPSM2::get();

        return view('PSM2.coordinator.listpelajar',compact('students'));
    }

    public function gradecoordinator($id){

        $data = ['id' => $id];

        return view('PSM1.coordinator.gradepage',$data);
    }

    public function gradecoordinator2($id){

        $data = ['id' => $id];

        return view('PSM2.coordinator.gradepage',$data);
    }

    public function markahPSM1Coordinator(Request $request){

        $data = $request->all();

        $this->coordinatorService->getMarkahPSM1Coordinator($data);
        
        return redirect()->route('listcgrade')->with('success', 'Student has been graded successfully');
    }

    public function markahPSM2Coordinator(Request $request){

        $data = $request->all();

        $this->coordinatorService->getMarkahPSM2Coordinator($data);
    
        return redirect()->route('listcgrade')->with('success', 'Student has been graded successfully');
    }

    public function fetchPanelNames(): array{

        return $this->coordinatorService->fetchPanelNames();
    }
    
    public function viewPanelsPSM1(){

        $users = User::all();

        return view('PSM1.coordinator.listpanel', ['panels' => $users]);
    }

    public function viewMergeData(ProjectLecturerMergerService $mergerService){

        $mergedData = $mergerService->mergePanelAndProjectDataWithMapping();

        return view('PSM1.coordinator.mldata', [
            'samples' => $mergedData['samplesWithMapping'],
            'labels' => $mergedData['labelsWithMapping'],
        ]);
    }



    public function deleteAllAssignedPanels(){

        try{
            return $this->coordinatorService->deleteAllAssignedPanels();
        }catch(\Exception $e){
            logger('Error auto assigning panels to students: ' . $e->getMessage());
        }
    }

    public function getMLData(ProjectLecturerMergerService $mergerService){

        $projectArea = $mergerService->mergePanelAndProjectDataWithMapping();
        $sampleCount = count($projectArea['samples']);
        $labelCount = count($projectArea['labels']);
        $samples = $projectArea['samples'];
        $labels = $projectArea['labels'];

        logger(json_encode($samples, JSON_PRETTY_PRINT));
        logger(json_encode($labels, JSON_PRETTY_PRINT));


        dd([
            'Total Panel (Label Count)' => $projectArea['panelCount'],  
            'Total Project Areas' => $projectArea['projectAreaCount'],  
            'Total Project Types' => $projectArea['projectTypeCount'],  
            'Total Samples' => $sampleCount,  
            'Total Labels' => $labelCount,
            'Asg count per panel' => $projectArea['panelAssignments'],
        ]);


        //103 panel -> label type
        //16 project area
        //2 project type
        //2567 samples and labels

        //low probability because large number of panels

    }

    // public function getProjectArea(CompareMachineLearningService $compareMachineLearningService){
    //     $mergeResult = $compareMachineLearningService->compareKernel();
    //     dd($mergeResult);
    // }

    public function compareKernelModel(CompareMachineLearningService $compareMachineLearningService){
        $mergeResult = $compareMachineLearningService->compareKernel();
        dd($mergeResult);
    }

}
