<?php

namespace App\Services;

use App\Models\StudentPSM2;
use App\Models\StudentPSM1;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StudentService 
{
    public function totalStudent(){
        
        $count = StudentPSM1::count();

        return $count;
    }

    public function totalStudent2(){

        $count = StudentPSM2::count();

        return $count;
    }

    public function getStudentPSM1()
    {
        $students = StudentPSM1::leftJoin('users as sv', 'students_psm1.supervisorId', '=', 'sv.id')
            ->leftJoin('users as panel_users', 'students_psm1.panelId', '=', 'panel_users.id')
            ->leftJoin('users as panel2_users', 'students_psm1.panel2Id', '=', 'panel2_users.id')
            ->leftJoin('users as panel_proposal_users', 'students_psm1.panelProposalId', '=', 'panel_proposal_users.id')
            ->select('students_psm1.id', 'students_psm1.name', 'students_psm1.course', 'students_psm1.matric','students_psm1.title','students_psm1.project_area','students_psm1.project_type','students_psm1.sessionpsm', 'sv.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name','panel_proposal_users.name as panel_proposal_name')
            ->orWhereNull('students_psm1.supervisorId')
            ->get();

        $students_with_supervisor = StudentPSM1::join('users as sv', 'students_psm1.supervisorId', '=', 'sv.id')
            ->leftJoin('users as panel_users', 'students_psm1.panel2Id', '=', 'panel_users.id')
            ->leftJoin('users as panel2_users', 'students_psm1.panelId', '=', 'panel2_users.id')
            ->leftJoin('users as panel_proposal_users', 'students_psm1.panelProposalId', '=', 'panel_proposal_users.id')
            ->select('students_psm1.id', 'students_psm1.name', 'students_psm1.course', 'students_psm1.matric','students_psm1.title','students_psm1.project_area','students_psm1.project_type','students_psm1.sessionpsm', 'sv.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name','panel_proposal_users.name as panel_proposal_name')
            ->get();

        $totalStudents = $students->concat($students_with_supervisor);

        // $totalStudents = StudentPSM1::all();

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

        $students = studentPSM1::get()->where('supervisorId', '=', Session::get('id'));
  
        return $students;
    }

    public function getStudentSupervisor2(){
        
        $students = studentPSM2::get()->where('supervisorId', '=', Session::get('id'));
  
        return $students;
    }

    public function getStudentPanel(){

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

    public function getSupervisors(){

        $supervisors = DB::table('supervisors')
            ->join('users', 'supervisors.userId','=', 'users.id')
            ->select('supervisors.id', 'users.name')
            ->get();

        return $supervisors;
    }

    public function getStudents(?int $supervisorId){

        $unassignedStudents = StudentPSM1::whereNull('supervisorId')->get(['id','course' ,'name'])->map(function ($student){
            $student->assigned = false;
            return $student;
        });
        
        $assignedStudents = StudentPSM1::where('supervisorId', $supervisorId)->get(['id', 'course','name'])->map(function ($student){
            $student->assigned = true;
            return $student;
        });
        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
    }

    public function assignStudent($studentId, $supervisorId){

        StudentPSM1::whereId($studentId)
            ->update(['supervisorId' => $supervisorId]);
    }

    public function unassignStudent($studentId){

        StudentPSM1::whereId($studentId)
            ->update(['supervisorId' => null]);
    }
}