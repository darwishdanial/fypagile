<?php

namespace App\Http\Controllers;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ProjectLecturerMergerService;
use App\Services\CoordinatorService;
use App\Services\StudentService;
use App\Services\CompareMachineLearningService;
use App\Jobs\EmailPanelAssignmentCompleteJob;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoordinatorController extends Controller
{
    protected $coordinatorService;
    protected $studentService;

    public function __construct(CoordinatorService $coordinatorService, StudentService $studentService){

        $this->coordinatorService = $coordinatorService;

        $this->studentService = $studentService;
    }

    //Home

    public function index()
    {
        $this->authorize('view coordinator dashboard');

        $userName = Auth::user()->name;

        return Inertia::render('Coordinator/Home/Index',[
            'userName' => $userName
        ]);
    }

    //PSM1

    public function PSM1ListStudents()
    {
        $this->authorize('view psm1 list students table');

        $students = $this->studentService->getStudentPSM1();
        $archivedStudents = StudentPSM1::onlyTrashed()->get();

        return Inertia::render('Coordinator/PSM1/ListStudents',[
            'students' => $students,
            'archivedStudents' => $archivedStudents
        ]);
    }

    public function PSM1ListPanels()
    {
        $this->authorize('view psm1 list panels table');

        return Inertia::render('Coordinator/PSM1/ListPanels');
    }

    public function PSM1AssignSupervisor()
    {
        $this->authorize('view psm1 assign supervisor table');

        return Inertia::render('Coordinator/PSM1/AssignSupervisor');
    }

    public function PSM1AssignProposalPanel()
    {
        $this->authorize('view psm1 assign proposal panel table');

        return Inertia::render('Coordinator/PSM1/AssignProposalPanel');
    }

    public function PSM1AssignPanel()
    {
        $this->authorize('view psm1 assign panel table');

        return Inertia::render('Coordinator/PSM1/AssignPSM1Panel');
    }

    public function PSM1ViewResult()
    {
        $this->authorize('view psm1 result table');

        return Inertia::render('Coordinator/PSM1/ViewResult');
    }

    public function PSM1EvaluationRurbric()
    {
        $this->authorize('view psm1 evaluation rubric');

        return Inertia::render('Coordinator/PSM1/EvaluationRubric');
    }

    public function PSM1GradeSupervision()
    {
        $this->authorize('view psm1 grade supervision table');

        return Inertia::render('Coordinator/PSM1/GradeSupervision');
    }

    public function PSM1GradeProposal()
    {
        $this->authorize('view psm1 grade proposal table');

        return Inertia::render('Coordinator/PSM1/GradeProposal');
    }

    public function PSM1Grade()
    {
        $this->authorize('view psm1 grade table');

        return Inertia::render('Coordinator/PSM1/GradePSM1');
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

    //PSM2

    public function PSM2ListStudents()
    {
        $this->authorize('view psm2 list students table');

        //$students = $this->studentService->getStudentPSM1();

        return Inertia::render('Coordinator/PSM2/ListStudents');
    }

    public function PSM2ListPanels()
    {
        $this->authorize('view psm2 list panels table');

        return Inertia::render('Coordinator/PSM2/ListPanels');
    }

    public function PSM2AssignPanel()
    {
        $this->authorize('view psm2 assign panel table');

        return Inertia::render('Coordinator/PSM2/AssignPSM2Panel');
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

    public function autoAssignPanelsToStudentsPSM1(){

        $user = Auth::user();
        $email = $user->email;
        $psmType = 'PSM1';
        return $this->coordinatorService->autoAssignPanelsToStudents($psmType, $email);

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
