<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rubric;
use Inertia\Inertia;
use App\Models\Criteria;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Score;

class GradingController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService){

        $this->studentService = $studentService;
    }

    public function PSM1GradeSupervision()
    {

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGradePSM1($id, 1);

        // dd($studentsDevelopment);

        $studentResearch = $this->studentService->getStudentsSupervisorGradePSM1($id, 2);

        // dd($studentResearch);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('rubricType',  1)
            ->where('roleType',  3)
            ->where('isEnable',  true)
            ->get();

            // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('rubricType',  2)
            ->where('roleType',  3)
            ->where('isEnable',  true)
            ->get();    

            // dd($rubricsResearch);

        $page = $userType == 1 ? 'Coordinator/PSM1/GradeSupervision' : 'Panel/PSM1/GradeSupervision';

        return Inertia::render($page, [
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM1GradePanel()
    {

        $id = Auth::user()->id;

        $userType = Auth::user()->role;


        $studentsDevelopment = $this->studentService->getStudentsPanelGradePSM1($id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGradePSM1($id, 2);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('rubricType',  1)
            ->where('roleType',  2)
            ->where('isEnable',  true)
            ->get();

        // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM1')
            ->where('rubricType',  2)
            ->where('roleType',  2)
            ->where('isEnable',  true)
            ->get();    

        $page = $userType == 1 ? 'Coordinator/PSM1/GradePSM1' : 'Panel/PSM1/GradePSM1';

        return Inertia::render($page,[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM1GradeCoordinator()
    {
        $id = Auth::user()->id;

        $students = StudentPSM1::all();

        $rubrics = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                ->where('PSMType',  'PSM1')
                ->where('roleType',  1)
                ->where('isEnable',  true)
                ->get();

        return Inertia::render('Coordinator/PSM1/GradePSM1Coordinator',[
            'students' => $students,
            'rubrics' => $rubrics,
            'id' => $id
        ]);
    }
    
    public function PSM1StoreScore(Request $request){

        // dd('panel id: '.$request->panel_id);

        $totalScore = array_sum($request->criteria);

        $weight = $request->total_weight;

        $finalScore = $weight/100 * $totalScore;

        $panelName = User::findOrFail($request->panel_id)->name;

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

        // dd($request->criteria,$request->student_id, $id, $request->comments,$request->total_weight, $totalScore, $finalScore, $request->rubric_id);
    }

    //Grade PSM2

    public function PSM2GradeSupervision()
    {

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGradePSM2($id, 1);

        // dd($studentsDevelopment);

        $studentResearch = $this->studentService->getStudentsSupervisorGradePSM2($id, 2);

        // dd($studentResearch);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('rubricType',  1)
            ->where('roleType',  3)
            ->where('isEnable',  true)
            ->get();

            // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('rubricType',  2)
            ->where('roleType',  3)
            ->where('isEnable',  true)
            ->get();    

            // dd($rubricsResearch);

        $page = $userType == 1 ? 'Coordinator/PSM2/GradeSupervision' : 'Panel/PSM2/GradeSupervision';

        return Inertia::render($page,[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM2GradePanel()
    {
        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsPanelGradePSM2($id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGradePSM2($id, 2);

        $rubricsDevelopment = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('rubricType',  1)
            ->where('roleType',  2)
            ->where('isEnable',  true)
            ->get();

        // dd($rubricsDevelopment);

        $rubricsResearch = Rubric::with(['criteria'])  // Only load criteria, not grading levels
            ->where('PSMType',  'PSM2')
            ->where('rubricType',  2)
            ->where('roleType',  2)
            ->where('isEnable',  true)
            ->get();    
        
        $page = $userType == 1 ? 'Coordinator/PSM2/GradePSM2' : 'Panel/PSM2/GradePSM2';

        return Inertia::render($page,[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM2GradeCoordinator()
    {
        $id = Auth::user()->id;

        $students = StudentPSM2::all();

        $rubrics = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                ->where('PSMType',  'PSM2')
                ->where('roleType',  1)
                ->where('isEnable',  true)
                ->get();

        return Inertia::render('Coordinator/PSM2/GradePSM2Coordinator',[
            'students' => $students,
            'rubrics' => $rubrics,
            'id' => $id
        ]);
    }

    public function PSM2StoreScore(Request $request){

        // dd('panel id: '.$request->panel_id);

        $totalScore = array_sum($request->criteria);

        $weight = $request->total_weight;

        $finalScore = $weight/100 * $totalScore;

        $panelName = User::findOrFail($request->panel_id)->name;

        Score::updateOrCreate(
            [
                'rubric_id' => $request->rubric_id,
                'student_psm2_id' => $request->student_id,
                'panel_id' => $request->panel_id,
            ],
            [
                'mark' => $finalScore,
                'comment' => $request->comments,
                'panel_name' => $panelName,
            ]
        );

        return redirect()->back()->with('success', 'Score successfully stored.');

        // dd($request->criteria,$request->student_id, $id, $request->comments,$request->total_weight, $totalScore, $finalScore, $request->rubric_id);
    }

}
