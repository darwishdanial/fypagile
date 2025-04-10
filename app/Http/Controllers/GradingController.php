<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Services\StudentService;
use Illuminate\Support\Facades\Auth;
use App\Services\GradingService;

class GradingController extends Controller
{
    protected $studentService;
    protected $gradingService;

    public function __construct(StudentService $studentService, GradingService $gradingService){

        $this->studentService = $studentService;
        $this->gradingService = $gradingService;
    }

    public function PSM1GradeSupervision()
    {

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGradePSM1($id, 1);

        $studentResearch = $this->studentService->getStudentsSupervisorGradePSM1($id, 2);

        $rubricsDevelopment = $this->gradingService->getRubrics('PSM1', 1, 3);

        $rubricsResearch = $this->gradingService->getRubrics('PSM1', 2, 3);

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

        $rubricsDevelopment = $this->gradingService->getRubrics('PSM1', 1, 2);
        
        $rubricsResearch = $this->gradingService->getRubrics('PSM1', 2, 2);

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

        $rubrics = $this->gradingService->getRubricsCoordinator('PSM1');

        return Inertia::render('Coordinator/PSM1/GradePSM1Coordinator',[
            'students' => $students,
            'rubrics' => $rubrics,
            'id' => $id
        ]);
    }
    
    public function PSM1StoreScore(Request $request){

        $this->gradingService->storeScore($request->all(), "PSM1");

        return redirect()->back()->with('success', 'Score successfully stored.');
    }

    //Grade PSM2

    public function PSM2GradeSupervision(){

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGradePSM2($id, 1);

        $studentResearch = $this->studentService->getStudentsSupervisorGradePSM2($id, 2);

        $rubricsDevelopment = $this->gradingService->getRubrics('PSM2', 1, 3);

        $rubricsResearch = $this->gradingService->getRubrics('PSM2', 2, 3);

        $page = $userType == 1 ? 'Coordinator/PSM2/GradeSupervision' : 'Panel/PSM2/GradeSupervision';

        return Inertia::render($page,[
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            'rubricsDevelopment' => $rubricsDevelopment,
            'rubricsResearch' => $rubricsResearch,
            'id' => $id
        ]);
    }

    public function PSM2GradePanel(){

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsPanelGradePSM2($id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGradePSM2($id, 2);

        $rubricsDevelopment = $this->gradingService->getRubrics('PSM2', 1, 2);

        $rubricsResearch = $this->gradingService->getRubrics('PSM2', 2, 2); 
        
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

        $rubrics = $this->gradingService->getRubricsCoordinator('PSM2');

        return Inertia::render('Coordinator/PSM2/GradePSM2Coordinator',[
            'students' => $students,
            'rubrics' => $rubrics,
            'id' => $id
        ]);
    }

    public function PSM2StoreScore(Request $request){

        $this->gradingService->storeScore($request->all(), 'PSM2');

        return redirect()->back()->with('success', 'Score successfully stored.');

    }

}
