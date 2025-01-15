<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Imports\StudentsPSM1Import;
use App\Imports\StudentsPSM2Import;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Student;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Services\StudentService;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService){

        $this->studentService = $studentService;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function index(){

        $students = Student::get();
  
        return view('students', compact('students'));
    }

    public function totalStudent(){

        return $this->studentService->totalStudent();
    }

    public function totalStudent2(){

        return $this->studentService->totalStudent2();
    }

    public function getStudentPSM1(){
        
        return $this->studentService->getStudentPSM1();
    }


    public function getStudentPSM2(){

        return $this->studentService->getStudentPSM2();
    }

    public function getStudentSupervisor(){

        return $this->studentService->getStudentSupervisor();
    }
    
    public function getStudentSupervisor2(){

        return $this->studentService->getStudentSupervisor2();
    }

    public function getStudentPanel(){

        return $this->studentService->getStudentPanel();
    }


    public function getStudentPanel2(){

        return $this->studentService->getStudentPanel2();
    }
        
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() {

        return Excel::download(new StudentsExport, 'students.xlsx');
    }
       
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() {

        Excel::import(new StudentsImport,request()->file('file'));

        return back();
    }

    public function getSupervisors(){

        $supervisors = $this->studentService->getSupervisors();

        return view('assign-students', compact(['supervisors']));
    }

    public function getStudents(Request $request){

        $students = $request->input('svId');

        return $this->studentService->getStudents($students);
    }

    public function assignStudent(Request $request){

        $request->validate([
            'id' => 'required|integer|exists:student_psm1,id',
            'svId' => 'required|integer|exists:supervisors,id',
        ]);

        return $this->studentService->assignStudent($request->id, $request->svId);
    }

    public function unassignStudent(Request $request){

        $request->validate([
            'id' => 'required|integer|exists:student_psm1,id',
            'svId' => 'required|integer|exists:supervisors,id',
        ]);

        return $this->studentService->assignStudent($request->id, $request->svId);
    }

    //import PSM1 student//

    // display import psm1 page
    public function studentPSM1(){

        $students = StudentPSM1::get();
  
        return view('PSM1.importpsm1', compact('students'));
    }

    public function importPSM1() {

        Excel::import(new StudentsPSM1Import,request()->file('file'));

        return back();
    }

    public function studentPSM2(){

        $students = StudentPSM2::get();
  
        return view('PSM2.importpsm2', compact('students'));
    }

    public function importPSM2() {

        Excel::import(new StudentsPSM2Import,request()->file('file'));

        return back();
    }

   

    
}
