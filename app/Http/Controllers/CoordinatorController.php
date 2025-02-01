<?php

namespace App\Http\Controllers;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ProjectLecturerMergerService;
use App\Services\CoordinatorService;
use App\Services\StudentService;
use App\Jobs\EmailPanelAssignmentCompleteJob;
use Illuminate\Support\Facades\Auth;

class CoordinatorController extends Controller
{
    protected $coordinatorService;
    protected $studentService;

    public function __construct(CoordinatorService $coordinatorService, StudentService $studentService){

        $this->coordinatorService = $coordinatorService;

        $this->studentService = $studentService;
    }

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
        
        $results = $this->coordinatorService->getResultPSM2();

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
            'samples' => $mergedData['samples'],
            'labels' => $mergedData['labels'],
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

}
