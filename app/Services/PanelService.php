<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\PanelHistory;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Imports\PanelsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

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
            ->leftJoin('students_psm1 as students_panel1', 'users.id', '=', 'students_panel1.panelId')
            ->leftJoin('students_psm1 as students_panel2', 'users.id', '=', 'students_panel2.panel2Id')
            ->select(
                'users.id', 
                'users.matricNo',
                'users.name',
                'users.role',
                'users.username',
                'users.email', 
                'users.isSupervisorPSM1',
                'users.isPanelPSM1',
                'users.isArchivePSM1',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name','users.role', 'users.username', 'users.email', 'users.isSupervisorPSM1', 'users.isPanelPSM1', 'users.isArchivePSM1')
            ->get();
    
        // Convert comma-separated student names into arrays
        $panelPSM1->transform(function ($panel) {
            $panel->students_sv_names = $panel->students_sv_names ? explode(',', $panel->students_sv_names) : [];
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
            ->leftJoin('students_psm1 as students_panel1', 'users.id', '=', 'students_panel1.panelId')
            ->leftJoin('students_psm1 as students_panel2', 'users.id', '=', 'students_panel2.panel2Id')
            ->select(
                'users.id', 
                'users.matricNo',
                'users.name',
                'users.username',
                'users.email', 
                'users.role',
                'users.isSupervisorPSM1',
                'users.isPanelPSM1',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name','users.role', 'users.username', 'users.email', 'users.isSupervisorPSM1', 'users.isPanelPSM1', 'users.isArchivePSM1')
            ->get();
    
        // Convert comma-separated student names into arrays
        $panelPSM1->transform(function ($panel) {
            $panel->students_sv_names = $panel->students_sv_names ? explode(',', $panel->students_sv_names) : [];
            $panel->students_panel1_names = $panel->students_panel1_names ? explode(',', $panel->students_panel1_names) : [];
            $panel->students_panel2_names = $panel->students_panel2_names ? explode(',', $panel->students_panel2_names) : [];
            return $panel;
        });
    
        return $panelPSM1;
    }

    public function getAssignProposalPanel() {

        $panelPSM1 = User::where('isProposalPanel', '1')->select('id', 'matricNo', 'name', 'username', 'email')->get();
    
        return $panelPSM1;
    }

    public function getAssignPanelPSM1() {

        $panelPSM1 = User::where('isPanelPSM1', '1')->select('id', 'matricNo', 'name', 'username', 'email')->get();
    
        return $panelPSM1;
    }

    public function getAssignPanelPSM2() {

        $panelPSM1 = User::where('isPanelPSM2', '1')->select('id', 'matricNo', 'name', 'username', 'email')->get();
    
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
                'users.role',
                'users.username',
                'users.email', 
                'users.isSupervisorPSM2',
                'users.isPanelPSM2',
                'users.isArchivePSM2',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name', 'users.role', 'users.username', 'users.email', 'users.isSupervisorPSM2', 'users.isPanelPSM2', 'users.isArchivePSM2')
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
                'users.role',
                'users.username',
                'users.email', 
                'users.isSupervisorPSM2',
                'users.isPanelPSM2',
                'users.isArchivePSM2',
                DB::raw('GROUP_CONCAT(DISTINCT students_sv.name) as students_sv_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel1.name) as students_panel1_names'),
                DB::raw('GROUP_CONCAT(DISTINCT students_panel2.name) as students_panel2_names')
            )
            ->groupBy('users.id', 'users.matricNo', 'users.name', 'users.role', 'users.username', 'users.email', 'users.isSupervisorPSM2', 'users.isPanelPSM2', 'users.isArchivePSM2')
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

    public function getPanelDashboardData()
    {
        $user = Auth::user();

        $studentsPSM1 = StudentPSM1::where("supervisorId", $user->id)->count();
        $studentsPSM2 = StudentPSM2::where("supervisorId", $user->id)->count();
        $panelsPSM1 = StudentPSM1::where("panelId", $user->id)
                                ->orWhere("panel2Id", $user->id)
                                ->count();
        $panelsPSM2 = StudentPSM2::where("panelId", $user->id)
                                ->orWhere("panel2Id", $user->id)
                                ->count();

        return [
            'userName' => $user->name,
            'studentsPSM1' => $studentsPSM1,
            'studentsPSM2' => $studentsPSM2,
            'panelsPSM1' => $panelsPSM1,
            'panelsPSM2' => $panelsPSM2,
        ];
    }


    public function createPanel(array $data)
    {

        try {

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
    
            $user = User::create($data);

            if($data['role'] === 1) {
                $user->assignRole('Coordinator');
            } else {
                $user->assignRole('Panel');
            }
    
            return redirect()->back()->with('success', 'Panel added successfully!');

        } catch (Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to add panel.');
        }

    }

    public function updatePanel($id, array $data)
    {

        try {

            $panel = User::findOrFail($id);

            if($data['role'] === 1) {
                $panel->assignRole('Coordinator');
            } else {
                $panel->assignRole('Panel');
            }

            if (!isset($data['password']) || empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }
    
            $panel->update($data);
    
            return redirect()->back()->with('success', 'Panel updated successfully!');

        } catch (Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update panel.');
        }
    }

    public function deletePanel($id)
    {

        try {

            $panel = User::findOrFail($id);
            
            // Check if there are related panel history records
            $hasPanelHistory = PanelHistory::where('panel_id', $id)->exists();

            if ($hasPanelHistory) {
                $panel->delete();
            }else{
                // Force delete the panel
                $panel->forceDelete();
            }

            return redirect()->back()->with('success', 'Panel deleted successfully.');

        } catch (Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete panel.');
        }

    }

    public function archivePanel($id, $psmType)
    {

        try {

            $panel = User::findOrFail($id);
            
            if ($psmType === 'PSM1') {
                $panel->update([
                    'role' => 2,
                    'isArchivePSM1' => 1,
                    'isSupervisorPSM1' => 0,
                    'isPanelPSM1' => 0,
                ]);
            } else {
                $panel->update([
                    'role' => 2,
                    'isArchivePSM2' => 1,
                    'isSupervisorPSM2' => 0,
                    'isPanelPSM2' => 0,
                ]);
            }

            $panel->assignRole('Panel');

            return redirect()->back()->with('success', 'Panel archived successfully.');

        } catch (Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to archive panel.');
        }

    }

    public function restorePanel($id, $psmType)
    {
        
        try {

            $panel = User::findOrFail($id);
        
            if ($psmType === 'PSM1') {
                $panel->update([
                    'isArchivePSM1' => 0,
                    'isSupervisorPSM1' => 1,
                    'isPanelPSM1' => 1,
                ]);
            } else {
                $panel->update([
                    'isArchivePSM2' => 0,
                    'isSupervisorPSM2' => 1,
                    'isPanelPSM2' => 1,
                ]);
            }
    
            return back()->with('success', 'Panel restore successfully.');

        } catch (Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to restore panel.');
        }

    }

    public function bulkArchivePanel(array $ids, $psmType)
    {

        try {

            if ($psmType === 'PSM1') {
                User::whereIn('id', $ids)->update([
                    'isArchivePSM1' => 1,
                    'isSupervisorPSM1' => 0,
                    'isPanelPSM1' => 0,
                ]);
            } else {
                User::whereIn('id', $ids)->update([
                    'isArchivePSM2' => 1,
                    'isSupervisorPSM2' => 0,
                    'isPanelPSM2' => 0,
                ]);
            }
    
            return redirect()->back()->with('success', 'Selected panels have been archived successfully!');

        } catch (Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to bulk archive panels.');
        }
    }

    public function getPanelSample()
    {
        $filePath = 'import_panels_sample_data.xlsx';
        
        if (!Storage::disk('public')->exists($filePath)) {
            return false;
        }
    
        return storage_path("app/public/$filePath");
    }

    public function importPanels($file)
    {
        
        try {

            $import = new PanelsImport();
            Excel::import($import, $file);

            $failures = Cache::get('panels_import_failures', []);

            if($failures) {
                Cache::forget('panels_import_failures');
                return redirect()->back()->with('warning', $failures);
            }

            return redirect()->back()->with('success', 'Panels imported successfully!');

        } catch (Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to bulk import panels.');
        }
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