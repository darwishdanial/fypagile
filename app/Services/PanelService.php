<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class PanelService 
{
    // public function getPanelPSM1(){

    //     $panelPSM1 = User::leftJoin('students_psm1 as students_sv', 'users.id', '=', 'students_sv.supervisorId')
    //         ->leftJoin('students_psm1 as students_proposal', 'users.id', '=', 'students_proposal.panelProposalId')
    //         ->leftJoin('students_psm1 as students_panel1', 'users.id', '=', 'students_panel1.panelId')
    //         ->leftJoin('students_psm1 as students_panel2', 'users.id', '=', 'students_panel2.panel2Id')
    //         ->select(
    //             'users.id', 
    //             'users.matricNo',
    //             'users.name',
    //             'users.username',
    //             'users.email', 
    //             'users.isSupervisor',
    //             'users.isPanel',

    //             'students_sv.name as students_sv_name', 
    //             'students_proposal.name as students_proposal_name', 
    //             'students_panel1.name as students_panel1_name',
    //             'students_panel2.name as students_panel2_name',
    //         )
    //         ->get();

    //     return $panelPSM1;

    // }

    public function getPanelPSM1() {

        $panelPSM1 = User::where('isArchivePSM1', '0')
            ->leftJoin('students_psm1 as students_sv', 'users.id', '=', 'students_sv.supervisorId')
            ->leftJoin('students_psm1 as students_proposal', 'users.id', '=', 'students_proposal.panelProposalId')
            ->leftJoin('students_psm1 as students_panel1', 'users.id', '=', 'students_panel1.panelId')
            ->leftJoin('students_psm1 as students_panel2', 'users.id', '=', 'students_panel2.panel2Id')
            ->select(
                'users.id', 
                'users.matricNo',
                'users.name',
                'users.username',
                'users.email', 
                'users.isSupervisorPSM1',
                'users.isProposalPanel',
                'users.isPanelPSM1',
                'users.isArchivePSM1',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_proposal.name) as students_proposal_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name', 'users.username', 'users.email', 'users.isSupervisorPSM1', 'users.isProposalPanel', 'users.isPanelPSM1', 'users.isArchivePSM1')
            ->get();
    
        // Convert comma-separated student names into arrays
        $panelPSM1->transform(function ($panel) {
            $panel->students_sv_names = $panel->students_sv_names ? explode(',', $panel->students_sv_names) : [];
            $panel->students_proposal_names = $panel->students_proposal_names ? explode(',', $panel->students_proposal_names) : [];
            $panel->students_panel1_names = $panel->students_panel1_names ? explode(',', $panel->students_panel1_names) : [];
            $panel->students_panel2_names = $panel->students_panel2_names ? explode(',', $panel->students_panel2_names) : [];
            return $panel;
        });

        //dd($panelPSM1->first());
    
        return $panelPSM1;
    }

    public function getPanelPSM1Archive() {

        $panelPSM1 = User::where('isArchivePSM1', '1')
            ->leftJoin('students_psm1 as students_sv', 'users.id', '=', 'students_sv.supervisorId')
            ->leftJoin('students_psm1 as students_proposal', 'users.id', '=', 'students_proposal.panelProposalId')
            ->leftJoin('students_psm1 as students_panel1', 'users.id', '=', 'students_panel1.panelId')
            ->leftJoin('students_psm1 as students_panel2', 'users.id', '=', 'students_panel2.panel2Id')
            ->select(
                'users.id', 
                'users.matricNo',
                'users.name',
                'users.username',
                'users.email', 
                'users.isSupervisorPSM1',
                'users.isProposalPanel',
                'users.isPanelPSM1',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_proposal.name) as students_proposal_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name', 'users.username', 'users.email', 'users.isSupervisorPSM1', 'users.isProposalPanel', 'users.isPanelPSM1', 'users.isArchivePSM1')
            ->get();
    
        // Convert comma-separated student names into arrays
        $panelPSM1->transform(function ($panel) {
            $panel->students_sv_names = $panel->students_sv_names ? explode(',', $panel->students_sv_names) : [];
            $panel->students_proposal_names = $panel->students_proposal_names ? explode(',', $panel->students_proposal_names) : [];
            $panel->students_panel1_names = $panel->students_panel1_names ? explode(',', $panel->students_panel1_names) : [];
            $panel->students_panel2_names = $panel->students_panel2_names ? explode(',', $panel->students_panel2_names) : [];
            return $panel;
        });
    
        return $panelPSM1;
    }

    public function getPanelPSM2(){

        $panelPSM2 = User::where('isArchivePSM2', '0')
            ->leftJoin('students_psm2 as students_sv', 'users.id', '=', 'students_sv.supervisorId')
            ->leftJoin('students_psm2 as students_panel1', 'users.id', '=', 'students_panel1.panelId')
            ->leftJoin('students_psm2 as students_panel2', 'users.id', '=', 'students_panel2.panel2Id')
            ->select(
                'users.id', 
                'users.matricNo',
                'users.name',
                'users.username',
                'users.email', 
                'users.isSupervisorPSM2',
                'users.isPanelPSM2',
                'users.isArchivePSM2',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name', 'users.username', 'users.email', 'users.isSupervisorPSM2', 'users.isPanelPSM2', 'users.isArchivePSM2')
            ->get();
    
        // Convert comma-separated student names into arrays
        $panelPSM2->transform(function ($panel) {
            $panel->students_sv_names = $panel->students_sv_names ? explode(',', $panel->students_sv_names) : [];
            $panel->students_panel1_names = $panel->students_panel1_names ? explode(',', $panel->students_panel1_names) : [];
            $panel->students_panel2_names = $panel->students_panel2_names ? explode(',', $panel->students_panel2_names) : [];
            return $panel;
        });

        //dd($panelPSM1->first());
    
        return $panelPSM2;
    }
    
    public function getPanelPSM2Archive(){

        $panelPSM2 = User::where('isArchivePSM2', '1')
            ->leftJoin('students_psm2 as students_sv', 'users.id', '=', 'students_sv.supervisorId')
            ->leftJoin('students_psm2 as students_panel1', 'users.id', '=', 'students_panel1.panelId')
            ->leftJoin('students_psm2 as students_panel2', 'users.id', '=', 'students_panel2.panel2Id')
            ->select(
                'users.id', 
                'users.matricNo',
                'users.name',
                'users.username',
                'users.email', 
                'users.isSupervisorPSM2',
                'users.isPanelPSM2',
                'users.isArchivePSM2',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name', 'users.username', 'users.email', 'users.isSupervisorPSM2', 'users.isPanelPSM2', 'users.isArchivePSM2')
            ->get();
    
        // Convert comma-separated student names into arrays
        $panelPSM2->transform(function ($panel) {
            $panel->students_sv_names = $panel->students_sv_names ? explode(',', $panel->students_sv_names) : [];
            $panel->students_panel1_names = $panel->students_panel1_names ? explode(',', $panel->students_panel1_names) : [];
            $panel->students_panel2_names = $panel->students_panel2_names ? explode(',', $panel->students_panel2_names) : [];
            return $panel;
        });

        //dd($panelPSM1->first());
    
        return $panelPSM2;
    }


    public function getPanel(){
        
        $panels = DB::table('users')
            ->where('isPanel','=', '1')
            ->get();
        return $panels;
    }

    public function getStudents($panelType, $panelId)
    {
        $students = collect();
        
        if ($panelType == 'panel1') {
            $students = StudentPSM1::all(['id', 'course', 'name', 'panelId', 'panel2Id'])->map(function ($student) use ($panelId) {
                $student->assigned = false;
                
                if ($student->panelId == $panelId) {
                    $student->assigned = true;
                } elseif ($student->panelId != null || $student->panel2Id == $panelId) {
                    return null;
                }
                
                return $student;
            })->filter(fn($student) => !is_null($student));
        } elseif ($panelType == 'panel2') {
            $students = StudentPSM1::all(['id', 'course', 'name', 'panelId', 'panel2Id'])->map(function ($student) use ($panelId) {
                $student->assigned = false;
                
                if ($student->panel2Id == $panelId) {
                    $student->assigned = true;
                } elseif ($student->panel2Id != null || $student->panelId == $panelId) {
                    return null;
                }
                
                return $student;
            })->filter(fn($student) => !is_null($student));
        }
        
        return $students->values()->all();

    }

    public function assignStudent($panelType, $studentId, $panelId){

        switch ($panelType) {
            case 'panel1':
                StudentPSM1::whereId($studentId)
                    ->update([
                        'panelId' => $panelId
                    ]);
                break;
        
            case 'panel2':
                StudentPSM1::whereId($studentId)
                    ->update([
                        'panel2Id' => $panelId
                    ]);
                break;
        
            default:
                throw new Exception("Invalid panel type: $panelType");
        }
    }

    public function unassignStudent($studentId, $panelType)
    {
        switch ($panelType) {
            case 'panel1':
                StudentPSM1::whereId($studentId)
                    ->update(['panelId' => null]);
                break;
            case 'panel2':
                StudentPSM1::whereId($studentId)
                    ->update(['panel2Id' => null]);
                break;
            default:
                throw new Exception("Invalid panel type: $panelType");
        }
    }

    // public function getPanelPSM2(){

    //     $panels = DB::table('users')
    //         ->where('isPanel','=', '1')
    //         ->get();

    //     return $panels;
    // }
    public function getStudentsPSM2($panelType, $panelId)
    {
        $students = collect();
        
        if ($panelType == 'panel1') {
            $students = StudentPSM2::all(['id', 'course', 'name', 'panelId', 'panel2Id'])->map(function ($student) use ($panelId) {
                $student->assigned = false;
                
                if ($student->panelId == $panelId) {
                    $student->assigned = true;
                } elseif ($student->panelId != null || $student->panel2Id == $panelId) {
                    return null;
                }
                
                return $student;
            })->filter(fn($student) => !is_null($student));
        } elseif ($panelType == 'panel2') {
            $students = StudentPSM2::all(['id', 'course', 'name', 'panelId', 'panel2Id'])->map(function ($student) use ($panelId) {
                $student->assigned = false;
                
                if ($student->panel2Id == $panelId) {
                    $student->assigned = true;
                } elseif ($student->panel2Id != null || $student->panelId == $panelId) {
                    return null;
                }
                
                return $student;
            })->filter(fn($student) => !is_null($student));
        }
        
        return $students->values()->all();

    }

    public function assignStudentPSM2($id, $panelType, $panelId)
    {
        switch ($panelType) {
            case 'panel1':
                StudentPSM2::whereId($id)
                    ->update(['panelId' => $panelId]);
                break;
            case 'panel2':
                StudentPSM2::whereId($id)
                    ->update(['panel2Id' => $panelId]);
                break;
            default:
                throw new Exception("Invalid panel type: $panelType");
        }
    }

    public function unassignStudentPSM2($id, $panelType)
    {
        switch ($panelType) {
            case 'panel1':
                StudentPSM1::whereId($id)
                    ->update(['panelId' => null]);
                break;
            case 'panel2':
                StudentPSM1::whereId($id)
                    ->update(['panel2Id' => null]);
                break;
            default:
                throw new Exception("Invalid panel type: $panelType");
        }
    }

    public function getPanelProposal(){
        
        $panels = DB::table('users')
                ->where('isPanel','=', '1')
                ->get();

        return $panels;
    }

    public function getStudentsProposal($panelId){

        $unassignedStudents = StudentPSM1::whereNull('panelProposalId')->get(['id','course' ,'name'])->map(function ($student){
            $student->assigned = false;
            return $student;
        });
        
        $assignedStudents = StudentPSM1::where('panelProposalId', $panelId)->get(['id', 'course','name'])->map(function ($student){
            $student->assigned = true;
            return $student;
        });

        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
    }

    public function assignStudentProposal($studentId, $panelId){

        StudentPSM1::whereId($studentId)
            ->update([
                'panelProposalId' => $panelId
            ]);
    }

    public function unassignStudentProposal($studentId){

        StudentPSM1::whereId($studentId)
            ->update([
                'panelProposalId' => null
            ]);
    }

    public function markahPSM1Panel($data)
    {
    
        $finalreport = ($data['abstract'] / 4 * 2) + ($data['completei1'] / 4 * 5) + ($data['completei2'] / 4 * 6) + ($data['completei3'] / 4 * 6) + ($data['completei4'] / 4 * 6) + ($data['writing3'] / 4 * 3) + $data['citation3'] / 4 * 2;
        $design = ($data['architecture'] / 4 * 3) + ($data['requirement'] / 4 * 3) + ($data['database'] / 4 * 4) + ($data['uml'] / 4 * 2) + ($data['gantt'] / 4 * 1) + ($data['interface'] / 4 * 2) + ($data['element'] / 4 * 5) + ($data['testing'] / 4 * 2) + ($data['coding1'] / 4 * 3);
        $presentation = (($data['appearance'] / 4 * 2) + ($data['understanding'] / 4 * 4) + $data['clarity'] / 4 * 2 + ($data['question'] / 4 * 2))/2 ;
        
        $totalshared = ($finalreport + $design) / 3;

        $total = $presentation + $totalshared;

    
        $studentId = $data['id'];
        $typeId = Session::get('id');
        $panelType = (StudentPSM1::find($studentId)->first()->panelId == $typeId) ? 'panel1' : 'panel2';
    
        $existingRecord = DB::table('result_psm1')
            ->where('studentId', $studentId)
            ->where('typeId', $typeId)
            ->first();
    
        if ($existingRecord) {
            // Update the existing record
            DB::table('result_psm1')
                ->where('studentId', $studentId)
                ->where('typeId', $typeId)
                ->update([
                    'finalreport' => $finalreport,
                    'design' => $design,
                    'presentation' => $presentation,
                    'totalshared' => $totalshared,
                    'total' => $total,
                ]);
        } else {
            // Insert a new record
            DB::table('result_psm1')->insert([
                'studentId' => $studentId,
                'typeId' => $typeId,
                'type' => $panelType,
                'finalreport' => $finalreport,
                'design' => $design,
                'presentation' => $presentation,
                'totalshared' => $totalshared,
                'total' => $total,
            ]);
        }
    }

    public function markahPSM2Panel($data){
        
        $shortpaper = ($data['originality']/4*1)+($data['technical']/4*2)+($data['clarity']/4*1)+($data['format']/4*1); //5
        $finalreport = ($data['abstract']/4*1)+($data['introduction']/4*1)+($data['literature']/4*2)+($data['methodology']/4*2)+($data['revised']/4*4)+($data['implementation']/4*6)+($data['revisedt']/4*5)+($data['conclusion']/4*2)+($data['writing2']/4*1)+($data['citation']/4*1); //25
        $system = ($data['scope']/4*3)+($data['coding']/4*5)+($data['completeness']/4*6)+($data['enhanced']/4*2)+($data['elements']/4*10)+($data['interface']/4*4); //30
        
        $presentation = (($data['appearance']/4*1)+($data['understanding']/4*3)+($data['clarity']/4*3)+($data['question']/4*3))/2;

        $totalshared = ($finalreport+$system+$shortpaper)/3;
        $total= $totalshared+$presentation;
        
        $studentId = $data['id'];
        $typeId = Session::get('id');
        $panelType = (StudentPSM2::find($studentId)->first()->panelId == $typeId) ? 'panel1' : 'panel2';

        // $panelType = StudentPSM2::find($studentId)->first()->panelType;
        $existingRecord = DB::table('result_psm2')
                ->where('studentId', $studentId)
                ->where('typeId', $typeId)
                ->first();
        
            if ($existingRecord) {
                // Update the existing record
                DB::table('result_psm2')
                    ->where('studentId', $studentId)
                    ->where('typeId', $typeId)
                    ->update([
                        'shortpaper' => $shortpaper,
                        'finalreport' => $finalreport,
                        'system' => $system,
                        'presentation' => $presentation,
                        'total' => $total,
                        'totalshared' => $totalshared,
                    ]);
            } else {
                // Insert a new record
                DB::table('result_psm2')->insert([
                    'studentId' => $studentId,
                    'typeId' => $typeId,
                    'type' => $panelType,
                    'shortpaper' => $shortpaper,
                    'finalreport' => $finalreport,
                    'system' => $system,
                    'presentation' => $presentation,
                    'total' => $total,
                    'totalshared' => $totalshared,
        
                ]);
            }

    }

    public function getStudentPanelProposal(){

        $students = studentPSM1::get()->where('panelProposalId', '=', Session::get('id'))->toArray();

        return$students;
    }

    public function markahProposal($data)
    {    
        // Check if the data already exists in the table
        $existingRecord = DB::table('result_proposal')
            ->where('studentId', $data['id'])
            ->where('panelProposalId', Session::get('id'))
            ->first();
    
        if ($existingRecord) {
            // Update the existing record
            DB::table('result_proposal')
                ->where('studentId', $data['id'])
                ->where('panelProposalId', Session::get('id'))
                ->update([
                    'approval' => $data['approval'],
                    'notes' => $data['notes'],
                ]);
        } else {
            // Insert a new record
            DB::table('result_proposal')->insert([
                'studentId' => $data['id'],
                'panelProposalId' => Session::get('id'),
                'approval' => $data['approval'],
                'notes' => $data['notes'],
            ]);
        }
    
    }

    public function viewMarkahProposal(){
        
        $totalResult = DB::table('result_proposal')
        ->join('students_psm1', 'result_proposal.studentId', '=', 'students_psm1.id')
        ->select('result_proposal.*', 'students_psm1.name as student_name')
        ->get();

        return $totalResult;
    }
}