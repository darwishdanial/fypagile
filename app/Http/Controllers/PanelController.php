<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\User;
use Session;
use Exception;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Services\PanelService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Rubric;
use App\Models\Criteria;
use App\Models\Score;


class PanelController extends Controller
{
    protected $studentService;
    protected $panelService;

    public function __construct(StudentService $studentService, PanelService $panelService){

        $this->studentService = $studentService;
        $this->panelService = $panelService;
    }

    public function index()
    {
        $this->authorize('view panel dashboard');

        $userName = Auth::user()->name;

        return Inertia::render('Panel/Home/Index',[
            'userName' => $userName
        ]);
    }

    //PSM1

    public function PSM1GradeSupervision(){

        $this->authorize('view psm1 grade supervision table');

        return Inertia::render('Panel/PSM1/GradeSupervision');
    }

    public function PSM1GradeProposal(){

        $this->authorize('view psm1 grade proposal table');

        return Inertia::render('Panel/PSM1/GradeProposal');
    }

    public function PSM1Grade(){

        $this->authorize('view psm1 grade table');
        $id = Auth::user()->id;

        $studentsDevelopment = $this->studentService->getStudentsPanelGradePSM1($id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGradePSM1($id, 2);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('isDevelopment',  true)
            ->get();

        // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('isResearch',  true)
            ->get();    

        return Inertia::render('Panel/PSM1/GradePSM1',[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM1StoreScore(Request $request){

        //dd('panel id: '.$request->panel_id);

        $totalScore = array_sum($request->criteria);

        $weight = $request->total_weight;

        $finalScore = $weight/100 * $totalScore;

        $panelName = User::findOrFail($request->panel_id)->name;

        // dd($panelName);

        Score::updateOrCreate(
            [
                'rubric_id' => $request->rubric_id,
                'student_psm1_id' => $request->student_id,
                'panel_id' => $request->panel_id,
            ],
            [
                'mark' => $finalScore,
                'comment' => $request->comments,
                'panel_name' => $panelName,
            ]
        );

        return redirect()->back()->with('success', 'Score successfully stored.');

    }

    //PSM2

    public function PSM2GradeSupervision(){

        $this->authorize('view psm2 grade supervision table');

        return Inertia::render('Panel/PSM2/GradeSupervision');
    }

    public function PSM2Grade(){

        $this->authorize('view psm2 grade table');

        return Inertia::render('Panel/PSM2/GradePSM2');
    }

    ////////////////////////////////////////////////////////////////////////


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
            $this->panelService->assignStudent($request->panelType,$request->id, $request->panelId);
            return "success assign PSM1";
        } catch (Exception $e) {
            logger('Exception:', );
            logger($e);
            return "error assign PSM1";
        }
    }

    public function unassignStudent(Request $request){

        try{
            $this->panelService->unassignStudent($request->id,$request->panelType);
            return "success unassign PSM1";
        } catch (Exception $e) {
            logger('Exception');
            logger($e);
            return "error unassign PSM1";
        }
    }

    public function getPanelPSM2(){

        $panels = $this->panelService->getPanelPSM2();

        return view('PSM2.panel.assignpanel', compact(['panels']));
    }

    public function getStudentsPSM2(Request $request){

        $students = $this->panelService->getStudentsPSM2($request->panelType, $request->panelId);

        return response()->json($students);
    }

    public function assignStudentPSM2(Request $request){

        try{
            $this->panelService->assignStudentPSM2($request->id, $request->panelType, $request->panelId);
            return "success assign PSM2";
        }catch(Exception $e){
            logger('Exception');
            logger($e);
            return "error assign PSM2";
        }
        
    }

    public function unassignStudentPSM2(Request $request){

        try{
            $this->panelService->unassignStudentPSM2($request->id, $request->panelType);
            return "success unassign PSM2";
        }catch(Exception $e){
            logger('Exception');
            logger($e);
            return "error unassign PSM2";
        }
        
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

        try{
            $this->panelService->assignStudentProposal($request->id, $request->panelId);
            return "success assign proposal";
        }catch(Exception $e){
            logger('Exception');
            logger($e);
            return "error assign proposal";
        }
    }

    public function unassignStudentProposal(Request $request){

        try{
            $this->panelService->unassignStudentProposal($request->id);
            return "success unassign proposal";
        }catch(Exception $e){
            logger('Exception');
            logger($e);
            return "error unassign proposal";
        }
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

    // public function gradeProposal($id){

    //     $data = ['id' => $id];

    //     return view('Proposal.panel.gradepageProposal',$data);
    // }

    public function markahProposal(Request $request){

        $this->panelService->markahProposal($request->all());
    
        return redirect()->route('studentproposal')->with('success', 'Student Has Been graded successfully');
    }
    
    public function viewMarkahProposal(){

        $totalResult = $this->panelService->viewMarkahProposal();

        return view('Proposal.panel.resultproposal', compact('totalResult'));
    }
}
