<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\SupervisorService;


class SupervisorController extends Controller
{
    protected $studentService;
    protected $supervisorService;

    public function __construct(StudentService $studentService, SupervisorService $supervisorService){

        $this->studentService = $studentService;
        $this->supervisorService = $supervisorService;
    }
    public function totalSupervisor(){

        return $this->supervisorService->totalSupervisor();
    }

    public function listsvstudent(){

        $students = $this->studentService->getStudentSupervisor();

        return view('PSM1.liststudentsv',compact('students'));
    }

    //display gradepage for supervisor
    public function creategradePSM1(Student $student){

        return view('PSM1.svgradeform', compact('student'));
    }

    public function gradePSM1(Request $request, Student $student){
        
        $request->validate([
            'logbook' => 'required',
            'meeting' => 'required',
            'ethic' => 'required',
            'independent' => 'required',
            'chapter1' => 'required',
            'chapter2' => 'required',
            'format' =>'required',
            'citation' => 'required',
            'chapter3' => 'required',
            'chapter4' => 'required',
            'format2' => 'required',
            'citation2' => 'required',
            'abstract' => 'required',
            'complete1' => 'required',
            'complete2' => 'required',
            'complete3' => 'required',
            'complete4' => 'required',
            'format3' => 'required',
            'citation3' => 'required',
        ]);

        try{
            $this->supervisorService->gradePSM1($request->all(), $student);
            return redirect()->route('listsvstudent')->with('success','Student has been graded successfully');
        }catch(\Exception $e){
            return redirect()->route('listsvstudent')->with('error','An error occurred while grading the student. Please try again.');
        }
        
    }

    public function listsvstudent2(){

        $students = $this->studentService->getStudentSupervisor2();
        
        return view('PSM2.liststudentsv',compact('students'));
    }

    //display gradepage for supervisor
    public function creategradePSM2(Student $student){

        return view('PSM2.svgradeform', compact('student'));
    }

    public function gradePSM2(Request $request, Student $student){
        
        $request->validate([
            'logbook' => 'required',
            'meeting' => 'required',
            'ethic' => 'required',
            'independent' => 'required',
        ]);

        try{
            $this->supervisorService->gradePSM2($request->all(), $student);
            return redirect()->route('listsvstudent2')->with('success','Student has been graded successfully');
        }catch(\Exception $e){
            return redirect()->route('listsvstudent2')->with('error','An error occurred while grading the student. Please try again.');
        }

    }

    //rubric agile PSM1
    public function listsvpelajar(){

        $students = $this->studentService->getStudentSupervisor();
        
        return view('PSM1.supervisor.listpelajarsv',compact('students'));
    }

    public function gradebaruPSM1($id){

        $data = ['id' => $id];

        return view('PSM1.svgradepage',$data);
    }

    public function markahPSM1(Request $request){

        $request->validate([
            'logbook' => 'required',
            'meetingf' => 'required',
            'work' => 'required',
            'selfreliance' => 'required',
            'iteration1' => 'required',
            'iteration2' => 'required',
            'writing' => 'required',
            'iteration3' => 'required',
            'iteration4' => 'required',
            'writing2' => 'required',
            'citation2' => 'required',
            'abstract' => 'required',
            'completei1' => 'required',
            'completei2' => 'required',
            'completei3' => 'required',
            'completei4' => 'required',
            'writing3' => 'required',
            'citation3' => 'required',
        ]);

        try{
            $this->supervisorService->markahPSM1($request->all());
            return redirect()->route('listsvpelajar')->with('success', 'Student has been graded successfully');
        } catch (\Exception $e) {
            return redirect()->route('listsvpelajar')->with('error', 'An error occurred while grading the student. Please try again.');
        }

    }
        

    //rubric agile PSM2
    public function listsvpelajar2(){

        $students = $this->studentService->getStudentSupervisor2();
        
        return view('PSM2.supervisor.listpelajarsv',compact('students'));
    }

    public function gradebaruPSM2($id){

        $data = ['id' => $id];

        return view('PSM2.svgradeform',$data);
    }

    public function markahPSM2(Request $request){

        try {
            $this->supervisorService->markahPSM2($request->all());
            return redirect()->route('listsvpelajar2')->with('success', 'Student has been graded successfully');
        } catch (\Exception $e) {
            return redirect()->route('listsvpelajar2')->with('error', 'An error occurred while grading the student. Please try again.');
        }
    }
    
}
