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
            ->select('students_psm1.id', 'students_psm1.name', 'students_psm1.course', 'students_psm1.matric','students_psm1.title','students_psm1.project_area','students_psm1.project_type','students_psm1.sessionpsm','students_psm1.cohort','students_psm1.phone','students_psm1.email','sv.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
            ->orWhereNull('students_psm1.supervisorId')
            ->get();

        $students_with_supervisor = StudentPSM1::join('users as sv', 'students_psm1.supervisorId', '=', 'sv.id')
            ->leftJoin('users as panel_users', 'students_psm1.panelId', '=', 'panel_users.id')
            ->leftJoin('users as panel2_users', 'students_psm1.panel2Id', '=', 'panel2_users.id')
            ->select('students_psm1.id', 'students_psm1.name', 'students_psm1.course', 'students_psm1.matric','students_psm1.title','students_psm1.project_area','students_psm1.project_type','students_psm1.sessionpsm','students_psm1.cohort','students_psm1.phone','students_psm1.email','sv.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
            ->get();

        $totalStudents = $students->concat($students_with_supervisor);

        // $totalStudents = StudentPSM1::all();

        return $totalStudents;
    }

    public function getStudentPSM1Archive(){

        $archivedStudents = StudentPSM1::onlyTrashed()
            ->leftJoin('users as sv', 'students_psm1.supervisorId', '=', 'sv.id')
            ->leftJoin('users as panel_users', 'students_psm1.panelId', '=', 'panel_users.id')
            ->leftJoin('users as panel2_users', 'students_psm1.panel2Id', '=', 'panel2_users.id')
            ->select(
                'students_psm1.id', 
                'students_psm1.name', 
                'students_psm1.course', 
                'students_psm1.matric',
                'students_psm1.title',
                'students_psm1.project_area',
                'students_psm1.project_type',
                'students_psm1.sessionpsm',
                'students_psm1.cohort',
                'students_psm1.phone',
                'students_psm1.email',
                'sv.name as sv_name', 
                'panel_users.name as panel_name',
                'panel2_users.name as panel2_name',
                'students_psm1.deleted_at' // To check when it was deleted
            )
            ->get();

        return $archivedStudents;

    }

    public function getStudentPSM2(){

        $students = StudentPSM2::leftJoin('users', 'students_psm2.supervisorId', '=', 'users.id')
        ->leftJoin('users as panel_users', 'students_psm2.panelId', '=', 'panel_users.id')
        ->leftJoin('users as panel2_users', 'students_psm2.panel2Id', '=', 'panel2_users.id')
        ->select('students_psm2.id','students_psm2.name', 'students_psm2.course', 'students_psm2.matric','students_psm2.title','students_psm2.project_area','students_psm2.project_type','students_psm2.sessionpsm','students_psm2.cohort','students_psm2.phone','students_psm2.email', 'users.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
        ->orWhereNull('students_psm2.supervisorId')
        ->get();
  
        $students_with_supervisor = StudentPSM2::join('users', 'students_psm2.supervisorId', '=', 'users.id')
        ->leftJoin('users as panel_users', 'students_psm2.panel2Id', '=', 'panel_users.id')
        ->leftJoin('users as panel2_users', 'students_psm2.panelId', '=', 'panel2_users.id')
        ->select('students_psm2.id','students_psm2.name', 'students_psm2.course', 'students_psm2.matric','students_psm2.title','students_psm2.project_area','students_psm2.project_type','students_psm2.sessionpsm','students_psm2.cohort','students_psm2.phone','students_psm2.email', 'users.name as sv_name', 'panel_users.name as panel_name','panel2_users.name as panel2_name')
        ->get();
  
        $totalstudents = $students->concat($students_with_supervisor);
        // return $students_with_supervisor;
        return $totalstudents;
    }

    public function getStudentPSM2Archive(){

        $archivedStudents = StudentPSM2::onlyTrashed()
            ->leftJoin('users as sv', 'students_psm2.supervisorId', '=', 'sv.id')
            ->leftJoin('users as panel_users', 'students_psm2.panelId', '=', 'panel_users.id')
            ->leftJoin('users as panel2_users', 'students_psm2.panel2Id', '=', 'panel2_users.id')
            ->select(
                'students_psm2.id', 
                'students_psm2.name', 
                'students_psm2.course', 
                'students_psm2.matric',
                'students_psm2.title',
                'students_psm2.project_area',
                'students_psm2.project_type',
                'students_psm2.sessionpsm',
                'students_psm2.cohort',
                'students_psm2.phone',
                'students_psm2.email',
                'sv.name as sv_name', 
                'panel_users.name as panel_name',
                'panel2_users.name as panel2_name',
                'students_psm2.deleted_at' // To check when it was deleted
            )
            ->get();

        return $archivedStudents;

    }

    public function getStudentsSupervisorPSM1(?int $supervisorId){

        $unassignedStudents = StudentPSM1::whereNull('supervisorId')
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panel2Id')
                    ->orWhere('panel2Id', '!=', $supervisorId);
            })
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panelId')
                    ->orWhere('panelId', '!=', $supervisorId);
            })
            ->get(['id','name','title' ,'project_area', 'project_type','supervisorId'])
            ->map(function ($student){
                $student->assigned = false;
                return $student;
            });
        
        $assignedStudents = StudentPSM1::where('supervisorId', $supervisorId)
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panel2Id')
                    ->orWhere('panel2Id', '!=', $supervisorId);
            })
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panelId')
                    ->orWhere('panelId', '!=', $supervisorId);
            })
            ->get(['id','name','title' ,'project_area', 'project_type','supervisorId'])
            ->map(function ($student){
                $student->assigned = true;
                return $student;
            });

        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
    }

    public function assignStudentsSupervisorPSM1($studentId, $supervisorId){

        StudentPSM1::whereId($studentId)
            ->update(['supervisorId' => $supervisorId]);
    }

    public function unassignStudentsSupervisorPSM1($studentId){

        StudentPSM1::whereId($studentId)
            ->update(['supervisorId' => null ]);
    }


    public function getStudentsPSM1lPanel(?int $panelId, int $type){

        $assignedStudents = ($type == 1) 
            ? StudentPSM1::where('panelId', $panelId)->get(['id','name','title' ,'project_area', 'project_type','panelId'])->map(function ($student){
            $student->assigned = true;
            return $student;
            })
            : StudentPSM1::where('panel2Id', $panelId)->get(['id','name','title' ,'project_area', 'project_type','panel2Id'])->map(function ($student){
            $student->assigned = true;
            return $student;
            });

        $unassignedStudents = ($type == 1) 
        ? StudentPSM1::whereNull('panelId')
            ->where(function($query) use ($panelId) {
                $query->whereNull('panel2Id')
                    ->orWhere('panel2Id', '!=', $panelId);
            })
            ->where(function($query) use ($panelId) {
                $query->whereNull('supervisorId')
                    ->orWhere('supervisorId', '!=', $panelId);
            })
            ->get(['id', 'name', 'title', 'project_area', 'project_type', 'panelId'])
        : StudentPSM1::whereNull('panel2Id')
            ->where(function($query) use ($panelId) {
                $query->whereNull('panelId')
                    ->orWhere('panelId', '!=', $panelId);
            })
            ->where(function($query) use ($panelId) {
                $query->whereNull('supervisorId')
                    ->orWhere('supervisorId', '!=', $panelId);
            })
            ->get(['id', 'name', 'title', 'project_area', 'project_type', 'panel2Id']);
        // logger($unassignedStudents);

        
            
        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
    }

    public function getStudentsPSM2lPanel(?int $panelId, int $type){

        $assignedStudents = ($type == 1) 
            ? StudentPSM2::where('panelId', $panelId)->get(['id','name','title' ,'project_area', 'project_type','panelId'])->map(function ($student){
            $student->assigned = true;
            return $student;
            })
            : StudentPSM2::where('panel2Id', $panelId)->get(['id','name','title' ,'project_area', 'project_type','panel2Id'])->map(function ($student){
            $student->assigned = true;
            return $student;
            });

            $unassignedStudents = ($type == 1) 
            ? StudentPSM2::whereNull('panelId')
                ->where(function($query) use ($panelId) {
                    $query->whereNull('panel2Id')
                        ->orWhere('panel2Id', '!=', $panelId);
                })
                ->where(function($query) use ($panelId) {
                    $query->whereNull('supervisorId')
                        ->orWhere('supervisorId', '!=', $panelId);
                })
                ->get(['id', 'name', 'title', 'project_area', 'project_type', 'panelId'])
            : StudentPSM2::whereNull('panel2Id')
                ->where(function($query) use ($panelId) {
                    $query->whereNull('panelId')
                        ->orWhere('panelId', '!=', $panelId);
                })
                ->where(function($query) use ($panelId) {
                    $query->whereNull('supervisorId')
                        ->orWhere('supervisorId', '!=', $panelId);
                })
                ->get(['id', 'name', 'title', 'project_area', 'project_type', 'panel2Id']);
        // logger($unassignedStudents);
        
            
        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
    }

    public function assignStudentsPSMPanel1PSM1($studentId, $panelId){

        StudentPSM1::whereId($studentId)
            ->update(['panelId' => $panelId]);
    }

    public function assignStudentsPSMPanel1PSM2($studentId, $panelId){

        StudentPSM2::whereId($studentId)
            ->update(['panelId' => $panelId]);
    }

    public function assignStudentsPSMPanel2PSM1($studentId, $panelId){

        StudentPSM1::whereId($studentId)
            ->update(['panel2Id' => $panelId]);
    }

    public function assignStudentsPSMPanel2PSM2($studentId, $panelId){

        StudentPSM2::whereId($studentId)
            ->update(['panel2Id' => $panelId]);
    }

    public function unassignStudentsPSMPanel1PSM1($studentId){

        StudentPSM1::whereId($studentId)
            ->update(['panelId' => null ]);
    }

    public function unassignStudentsPSMPanel1PSM2($studentId){

        StudentPSM2::whereId($studentId)
            ->update(['panelId' => null ]);
    }

    public function unassignStudentsPSMPanel2PSM1($studentId){

        StudentPSM1::whereId($studentId)
            ->update(['panel2Id' => null ]);
    }

    public function unassignStudentsPSMPanel2PSM2($studentId){

        StudentPSM2::whereId($studentId)
            ->update(['panel2Id' => null ]);
    }

    public function getStudentsSupervisorPSM2(?int $supervisorId){

        $unassignedStudents = StudentPSM2::whereNull('supervisorId')
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panel2Id')
                    ->orWhere('panel2Id', '!=', $supervisorId);
            })
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panelId')
                    ->orWhere('panelId', '!=', $supervisorId);
            })
            ->get(['id','name','title' ,'project_area', 'project_type','supervisorId'])
            ->map(function ($student){
                $student->assigned = false;
                return $student;
            });
        
        $assignedStudents = StudentPSM2::where('supervisorId', $supervisorId)
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panel2Id')
                    ->orWhere('panel2Id', '!=', $supervisorId);
            })
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panelId')
                    ->orWhere('panelId', '!=', $supervisorId);
            })
            ->get(['id','name','title' ,'project_area', 'project_type','supervisorId'])
            ->map(function ($student){
                $student->assigned = true;
                return $student;
            });

        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
    }

    public function assignStudentsSupervisorPSM2($studentId, $supervisorId){

        StudentPSM2::whereId($studentId)
            ->update(['supervisorId' => $supervisorId]);
    }

    public function unassignStudentsSupervisorPSM2($studentId){

        StudentPSM2::whereId($studentId)
            ->update(['supervisorId' => null ]);
    }

    public function getStudentsSupervisorGradePSM1(?int $supervisorId, int $type){

        $assignedStudents = ($type == 1 ) ? 
            StudentPSM1::where('supervisorId', $supervisorId)->where('project_type', "System Development")->get()
            :StudentPSM1::where('supervisorId', $supervisorId)->where('project_type', "Research Based")->get();

        return $assignedStudents;
    }

    public function getStudentsSupervisorGradePSM2(?int $supervisorId, int $type){

        $assignedStudents = ($type == 1 ) ? 
            StudentPSM2::where('supervisorId', $supervisorId)->where('project_type', "System Development")->get()
            :StudentPSM2::where('supervisorId', $supervisorId)->where('project_type', "Research Based")->get();

        return $assignedStudents;
    }

    public function getStudentsPanelGradePSM1(?int $panelId, int $type){

        $assignedStudents = ($type == 1 ) ? 
            StudentPSM1::where(function ($query) use ($panelId) {
                $query->where('panelId', $panelId)
                    ->orWhere('panel2Id', $panelId);
            })
            ->where('project_type', "System Development")
            ->get()
            :StudentPSM1::where(function ($query) use ($panelId) {
                $query->where('panelId', $panelId)
                    ->orWhere('panel2Id', $panelId);
            })
            ->where('project_type', "Research Based")
            ->get();
    

        return $assignedStudents;
    }

    public function getStudentsPanelGradePSM2(?int $panelId, int $type){

        $assignedStudents = ($type == 1 ) ? 
            StudentPSM2::where(function ($query) use ($panelId) {
                $query->where('panelId', $panelId)
                    ->orWhere('panel2Id', $panelId);
            })
            ->where('project_type', "System Development")
            ->get()
            :StudentPSM2::where(function ($query) use ($panelId) {
                $query->where('panelId', $panelId)
                    ->orWhere('panel2Id', $panelId);
            })
            ->where('project_type', "Research Based")
            ->get();
    

        return $assignedStudents;
    }






    public function getStudentsSupervisorLama(?int $supervisorId){

        $unassignedStudents = StudentPSM1::whereNull('supervisorId')->get(['id','title' ,'project_area', 'project_type','supervisorId'])->map(function ($student){
            $student->assigned = false;
            return $student;
        });
        
        $assignedStudents = StudentPSM1::where('supervisorId', $supervisorId)->get(['id','title' ,'project_area', 'project_type','supervisorId'])->map(function ($student){
            $student->assigned = true;
            return $student;
        });
        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
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

    

    // public function assignStudent($studentId, $supervisorId){

    //     StudentPSM1::whereId($studentId)
    //         ->update(['supervisorId' => $supervisorId]);
    // }

    public function unassignStudent($studentId){

        StudentPSM1::whereId($studentId)
            ->update(['supervisorId' => null]);
    }
}