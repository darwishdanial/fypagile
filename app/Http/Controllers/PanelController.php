<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Session;
use Exception;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\PanelService;


class PanelController extends Controller
{
    protected $studentService;
    protected $panelService;

    public function __construct(StudentService $studentService, PanelService $panelService){

        $this->studentService = $studentService;
        $this->panelService = $panelService;
    }
    public function getPanel(){

        $panels = $this->panelService->getPanel();
        return view('PSM1.panel.assignpanel', compact(['panels']));
    }

    public function getStudents(Request $request){

        $students = $this->panelService->getStudents($request->panelType, $request->panelId);

        return response()->json($students);
    }

    public function assignStudent(Request $request){

        try {
            $res = "success";
            $this->panelService->assignStudent($request->panelType,$request->id, $request->panelId);
            return $res;
        } catch (Exception $e) {
            error_log('Exception');
            error_log($e);
        }
    }

    public function unassignStudent(Request $request){

        return $this->panelService->unassignStudent($request->id,$request->panelType);
    }

    public function getPanelPSM2(){

        $panels = $this->panelService->getPanel();

        return view('PSM2.panel.assignpanel', compact(['panels']));
    }

    public function getStudentsPSM2(Request $request){

        $students = $this->panelService->getStudentsPSM2($request->panelType, $request->panelId);

        return response()->json($students);
    }

    public function assignStudentPSM2(Request $request){

        $result = $this->panelService->assignStudentPSM2($request->id, $request->panelType, $request->panelId);
        
        return response()->json(['status' => $result]);
    }

    public function unassignStudentPSM2(Request $request){

        $result = $this->panelService->unassignStudentPSM2($request->id, $request->panelType);
        
        return response()->json(['status' => $result]);
    }

    public function getPanelProposal(){

        $panels = $this->panelService->getPanel();

        return view('Proposal.panel.assignpanel', compact(['panels']));
    }

    public function getStudentsProposal(Request $request){

        $students = $this->panelService->getStudentsProposal($request->panelId);

        return $students;
    }

    public function assignStudentProposal(Request $request){
        
        return $this->panelService->assignStudentProposal($request->id, $request->panelId);
    }

    public function unassignStudentProposal(Request $request){

        return $this->panelService->unassignStudentProposal($request->id);
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

    public function markahPSM1Panel(Request $request){

        $this->panelService->markahPSM1Panel($request->all());
    
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

        $this->panelService->markahPSM2Panel($request->all());

        return redirect()->route('listpanelpelajar2')->with('success','Student Has Been graded successfully');
    }

    public function getStudentPanelProposal(){

        $students = $this->panelService->getStudentPanelProposal();

        return view('Proposal.panel.liststudent',compact('students'));
    }

    public function gradeProposal($id){

        $data = ['id' => $id];

        return view('Proposal.panel.gradepageProposal',$data);
    }

    public function markahProposal(Request $request){

        $this->panelService->markahProposal($request->all());
    
        return redirect()->route('studentproposal')->with('success', 'Student Has Been graded successfully');
    }
    
    public function viewMarkahProposal(){

        $totalResult = $this->panelService->viewMarkahProposal();

        return view('Proposal.panel.resultproposal', compact('totalResult'));
    }
}
