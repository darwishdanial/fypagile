<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use App\Imports\StudentsPSM1Import;
use App\Imports\StudentsPSM2Import;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Student;
use App\Models\User;
use App\Models\Supervisor;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Illuminate\Support\Facades\DB;
use Session;

class StudentController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function index(){
        $students = Student::get();
  
        return view('students', compact('students'));
    }

    public function totalStudent(){
        $count = StudentPSM1::count();
        return ($count);
    }

    public function totalStudent2(){
        $count = StudentPSM2::count();
        return ($count);
    }

    public function getStudentPSM1()
    {
    $students = StudentPSM1::leftJoin('users as sv', 'students_psm1.supervisorId', '=', 'sv.id')
        ->leftJoin('users as panel_users', 'students_psm1.panelId', '=', 'panel_users.id')
        ->leftJoin('users as panel2_users', 'students_psm1.panel2Id', '=', 'panel2_users.id')
        ->select('students_psm1.id', 'students_psm1.name', 'students_psm1.course', 'students_psm1.matric','students_psm1.title', 'sv.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
        ->orWhereNull('students_psm1.supervisorId')
        ->get();

    $students_with_supervisor = StudentPSM1::join('users as sv', 'students_psm1.supervisorId', '=', 'sv.id')
        ->leftJoin('users as panel_users', 'students_psm1.panel2Id', '=', 'panel_users.id')
        ->leftJoin('users as panel2_users', 'students_psm1.panelId', '=', 'panel2_users.id')
        ->select('students_psm1.id', 'students_psm1.name', 'students_psm1.course', 'students_psm1.matric','students_psm1.title', 'sv.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
        ->get();

    $totalStudents = $students->concat($students_with_supervisor);

    return $totalStudents;
    }


    public function getStudentPSM2(){

        $students = StudentPSM2::leftJoin('users', 'students_psm2.supervisorId', '=', 'users.id')
        ->leftJoin('users as panel_users', 'students_psm2.panelId', '=', 'panel_users.id')
        ->leftJoin('users as panel2_users', 'students_psm2.panel2Id', '=', 'panel2_users.id')
        ->select('students_psm2.id','students_psm2.name', 'students_psm2.course', 'students_psm2.matric','students_psm2.title', 'users.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
        ->orWhereNull('students_psm2.supervisorId')
        ->get();
  
        $students_with_supervisor = StudentPSM2::join('users', 'students_psm2.supervisorId', '=', 'users.id')
        ->leftJoin('users as panel_users', 'students_psm2.panel2Id', '=', 'panel_users.id')
        ->leftJoin('users as panel2_users', 'students_psm2.panelId', '=', 'panel2_users.id')
        ->select('students_psm2.id','students_psm2.name', 'students_psm2.course', 'students_psm2.matric','students_psm2.title', 'users.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
        ->get();
  
        $totalstudents = $students->concat($students_with_supervisor);
        // return $students_with_supervisor;
        return $totalstudents;
    }

    public function getStudentSupervisor(){
        //kena check dkt table supervisor
        $students = studentPSM1::get()->where('supervisorId', '=', Session::get('id'));
  
        return $students;
    }
    
    public function getStudentSupervisor2(){
        //kena check dkt table supervisor
        $students = studentPSM2::get()->where('supervisorId', '=', Session::get('id'));
  
        return $students;
    }

    public function getStudentPanel()
    {
        $panelId = Session::get('id');
        
        $students = studentPSM1::where('panelId', '=', $panelId)
                                ->orWhere('panel2Id', '=', $panelId)
                                ->get()
                                ->toArray();
        
        return $students;
    }


    public function getStudentPanel2(){
        $panelId = Session::get('id');

        $students = studentPSM2::where('panelId', '=', $panelId)
                                ->orWhere('panel2Id', '=', $panelId)
                                ->get()
                                ->toArray();
        return $students;
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

    public function getSupervisors()
    {
      
        $supervisors = DB::table('supervisors')
            ->join('users', 'supervisors.userId','=', 'users.id')
            ->select('supervisors.id', 'users.name')
            ->get();
        return view('assign-students', compact(['supervisors']));
    }

    public function getStudents(Request $request)
    {
        $unassignedStudents = StudentPSM1::whereNull('supervisorId')->get(['id','course' ,'name'])->map(function ($student){
            $student->assigned = false;
            return $student;
        });
        
        $assignedStudents = StudentPSM1::where('supervisorId', $request->svId)->get(['id', 'course','name'])->map(function ($student){
            $student->assigned = true;
            return $student;
        });
        $students = $assignedStudents->merge($unassignedStudents);
        return $students;
    }

    public function assignStudent(Request $request){
        $res = "success";

        StudentPSM1::whereId($request->id)
            ->update([
                'supervisorId' => $request->svId
            ]);
        return $res;
    }

    public function unassignStudent(Request $request){
        $res = "success";
        StudentPSM1::whereId($request->id)
            ->update([
                'supervisorId' => null
            ]);
        return $res;
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
