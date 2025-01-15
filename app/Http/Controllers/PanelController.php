<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Session;
use Exception;
use Illuminate\Http\Request;
use App\Services\StudentService;


class PanelController extends Controller
{

    protected $studentService;

    public function __construct(StudentService $studentService){

        $this->studentService = $studentService;
    }
    public function getPanel(){

        $panels = DB::table('users')
            ->where('isPanel','=', '1')
            ->get();
        return view('PSM1.panel.assignpanel', compact(['panels']));
    }

    public function getStudents(Request $request){
        try {
            if($request->panelType == 'panel1'){
                $students = StudentPSM1::all(['id', 'course', 'name','panelId', 'panel2Id'])->map(function ($student) use ($request) {
                    $student->assigned = false;
                    // if checks for panelId that is self, eg: currentPanel
                    if($student->panelId == $request->panelId){
                        $student->assigned = true;
                    }
                    // else checks for panelId not self, eg: null or other panels
                    else{
                        // if checks for other panels
                        if($student->panelId != null || $student->panel2Id == $request->panelId){
                            return null;
                        }
                    }
                    return $student;
                })->filter(function ($student) use ($request){
                    $isNotNull = !is_null($student);
                    return $isNotNull;
                });
                error_log($students);
            }
            else if($request->panelType == 'panel2'){
                $students = StudentPSM1::all(['id', 'course', 'name', 'panelId', 'panel2Id'])->map(function ($student) use ($request) {
                    $student->assigned = false;
                    // if checks for panelId that is self, eg: currentPanel
                    if($student->panel2Id == $request->panelId){
                        $student->assigned = true;
                    }
                    // else checks for panelId not self, eg: null or other panels
                    else{
                        // if checks for other panels
                        if($student->panel2Id != null || $student->panelId == $request->panelId){
                            return null;
                        }
                    }
                    return $student;
                })->filter(function ($student) use ($request){
                    $isNotNull = !is_null($student);
                    return $isNotNull;
                });
                error_log($students);
            }
            return $students->collect()->values()->all();
        } catch (Exception $e) {
            error_log('Exception');
            error_log($e);
        }
    }

    public function assignStudent(Request $request){
        try {
            $res = "success";
            if($request->panelType == 'panel1'){
                StudentPSM1::whereId($request->id)
                ->update([
                    'panelId' => $request->panelId
                ]);
            }
            else if($request->panelType == 'panel2'){
                StudentPSM1::whereId($request->id)
                ->update([
                    'panel2Id' => $request->panelId
                ]);
            }
            return $res;
        } catch (Exception $e) {
            error_log('Exception');
            error_log($e);
        }
    }

    public function unassignStudent(Request $request){
        try {
            error_log($request);
            $res = "success";
            if($request->panelType == 'panel1'){
                StudentPSM1::whereId($request->id)
                ->update([
                    'panelId' => null
                ]);
            }
            else if($request->panelType == 'panel2'){
                StudentPSM1::whereId($request->id)
                ->update([
                    'panel2Id' => null
                ]);
            }
            return $res;
        } catch (Exception $e) {
            error_log('Exception');
            error_log($e);
        }
    }

    public function getPanelPSM2(){
        $panels = DB::table('users')
            ->where('isPanel','=', '1')
            ->get();
        return view('PSM2.panel.assignpanel', compact(['panels']));
    }

    public function getStudentsPSM2(Request $request){
        try {
            if($request->panelType == 'panel1'){
                $students = StudentPSM2::all(['id', 'course', 'name','panelId', 'panel2Id'])->map(function ($student) use ($request) {
                    $student->assigned = false;
                    // if checks for panelId that is self, eg: currentPanel
                    if($student->panelId == $request->panelId){
                        $student->assigned = true;
                    }
                    // else checks for panelId not self, eg: null or other panels
                    else{
                        // if checks for other panels
                        if($student->panelId != null || $student->panel2Id == $request->panelId){
                            return null;
                        }
                    }
                    return $student;
                })->filter(function ($student) use ($request){
                    $isNotNull = !is_null($student);
                    return $isNotNull;
                });
                error_log($students);
            }
            else if($request->panelType == 'panel2'){
                $students = StudentPSM2::all(['id', 'course', 'name', 'panelId', 'panel2Id'])->map(function ($student) use ($request) {
                    $student->assigned = false;
                    // if checks for panelId that is self, eg: currentPanel
                    if($student->panel2Id == $request->panelId){
                        $student->assigned = true;
                    }
                    // else checks for panelId not self, eg: null or other panels
                    else{
                        // if checks for other panels
                        if($student->panel2Id != null || $student->panelId == $request->panelId){
                            return null;
                        }
                    }
                    return $student;
                })->filter(function ($student) use ($request){
                    $isNotNull = !is_null($student);
                    return $isNotNull;
                });
                error_log($students);
            }
            return $students->collect()->values()->all();
        } catch (Exception $e) {
            error_log('Exception');
            error_log($e);
        }
    }

    public function assignStudentPSM2(Request $request){
        try {
            $res = "success";
            if($request->panelType == 'panel1'){
                StudentPSM2::whereId($request->id)
                ->update([
                    'panelId' => $request->panelId
                ]);
            }
            else if($request->panelType == 'panel2'){
                StudentPSM2::whereId($request->id)
                ->update([
                    'panel2Id' => $request->panelId
                ]);
            }
            return $res;
        } catch (Exception $e) {
            error_log('Exception');
            error_log($e);
        }
    }

    public function unassignStudentPSM2(Request $request){
        try {
            error_log($request);
            $res = "success";
            if($request->panelType == 'panel1'){
                StudentPSM2::whereId($request->id)
                ->update([
                    'panelId' => null
                ]);
            }
            else if($request->panelType == 'panel2'){
                StudentPSM2::whereId($request->id)
                ->update([
                    'panel2Id' => null
                ]);
            }
            return $res;
        } catch (Exception $e) {
            error_log('Exception');
            error_log($e);
        }
    }

    public function getPanelProposal(){
        $panels = DB::table('users')
            ->where('isPanel','=', '1')
            ->get();
        return view('Proposal.panel.assignpanel', compact(['panels']));
    }

    public function getStudentsProposal(Request $request){
        $unassignedStudents = StudentPSM1::whereNull('panelProposalId')->get(['id','course' ,'name'])->map(function ($student){
            $student->assigned = false;
            return $student;
        });
        
        $assignedStudents = StudentPSM1::where('panelProposalId', $request->panelId)->get(['id', 'course','name'])->map(function ($student){
            $student->assigned = true;
            return $student;
        });
        $students = $assignedStudents->merge($unassignedStudents);
        return $students;
    }

    public function assignStudentProposal(Request $request){
        $res = "success";

        StudentPSM1::whereId($request->id)
            ->update([
                'panelProposalId' => $request->panelId
            ]);
        return $res;
    }

    public function unassignStudentProposal(Request $request){
        $res = "success";
        StudentPSM1::whereId($request->id)
            ->update([
                'panelProposalId' => null
            ]);
        return $res;
    }

    public function listpanelpelajar(){
        $students = $this->studentService->getStudentPanel();
        return view('PSM1.panel.listpelajarsv',compact('students'));
    }

    public function listpanelpelajar2(){
        $students = $this->studentService->getStudentPanel2();
        return view('PSM2.panel.listpelajarsv',compact('students'));
    }

    public function gradePSM1Panel($id){
        $data = ['id' => $id];
        return view('PSM1.panel.gradepagePSM1',$data);
    }

    public function gradePSM2Panel($id){
        $data = ['id' => $id];
        return view('PSM2.panel.gradepagePSM2',$data);
    }

        public function markahPSM1Panel(Request $request)
        {

            $data = $request->all();
        
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
        
            return redirect()->route('listpanelpelajar')->with('success', 'Student has been graded successfully');
        }
        

    public function markahPSM2Panel(Request $request){
        
        $request->validate([
            'originality' => 'required',
            'technical' => 'required',
            'clarity' => 'required',
            'format' => 'required',
            'abstract' => 'required',
            'introduction' => 'required',
            'literature' => 'required',
            'methodology' => 'required',
            'revised' => 'required',
            'implementation' => 'required',
            'revisedt' => 'required',
            'conclusion' => 'required',
            'writing2' => 'required',
            'citation' => 'required',
        ]);

        $data = $request->all();
        
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

        return redirect()->route('listpanelpelajar2')->with('success','Student Has Been graded successfully');
        // return view('PSM1.listpelajarsv', compact('students'));
    }

    public function getStudentPanelProposal(){
        $students = studentPSM1::get()->where('panelProposalId', '=', Session::get('id'))->toArray();
        return view('Proposal.panel.liststudent',compact('students'));
    }

    public function gradeProposal($id){
        $data = ['id' => $id];
        return view('Proposal.panel.gradepageProposal',$data);
    }

    public function markahProposal(Request $request)
    {
        $data = $request->all();
    
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
    
        return redirect()->route('studentproposal')->with('success', 'Student Has Been graded successfully');
    }
    
    public function viewMarkahProposal(){
        $totalResult = DB::table('result_proposal')
        ->join('students_psm1', 'result_proposal.studentId', '=', 'students_psm1.id')
        ->select('result_proposal.*', 'students_psm1.name as student_name')
        ->get();

        return view('Proposal.panel.resultproposal', compact('totalResult'));
    }
}
