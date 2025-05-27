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

        $this->authorize('view psm1 grade supervision');

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGrade("PSM1",$id, 1);

        $studentResearch = $this->studentService->getStudentsSupervisorGrade("PSM1",$id, 2);

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

        $this->authorize('view psm1 grade panel');

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsPanelGrade("PSM1",$id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGrade("PSM1",$id, 2);

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
        $this->authorize('view psm1 grade coordinator');

        $id = Auth::user()->id;

        $gradeData = $this->gradingService->getRubricsCoordinator('PSM1');

        return Inertia::render('Coordinator/PSM1/GradePSM1Coordinator',[
            ...$gradeData,
            'id' => $id
        ]);
    }
    
    public function PSM1StoreScore(Request $request){

        $this->authorize('store psm1 score');

        $this->gradingService->storeScore($request->all(), "PSM1");

        return redirect()->back()->with('success', 'Score successfully stored.');
    }

    public function PSM1DeleteScore($id){

        $this->authorize('delete psm1 score');

        return $this->gradingService->deleteScore($id);

    }

    //Grade PSM2

    public function PSM2GradeSupervision(){

        $this->authorize('view psm2 grade supervision');

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsSupervisorGrade("PSM2",$id, 1);

        $studentResearch = $this->studentService->getStudentsSupervisorGrade("PSM2",$id, 2);

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

        $this->authorize('view psm2 grade panel');

        $id = Auth::user()->id;

        $userType = Auth::user()->role;

        $studentsDevelopment = $this->studentService->getStudentsPanelGrade("PSM2",$id, 1);

        $studentResearch = $this->studentService->getStudentsPanelGrade("PSM2",$id, 2);

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
        $this->authorize('view psm2 grade coordinator');

        $id = Auth::user()->id;

        $gradeData = $this->gradingService->getRubricsCoordinator('PSM2');

        return Inertia::render('Coordinator/PSM2/GradePSM2Coordinator',[
             ...$gradeData,
            'id' => $id
        ]);
    }

    public function PSM2StoreScore(Request $request){

        $this->authorize('store psm2 score');
        
        $this->gradingService->storeScore($request->all(), 'PSM2');

        return redirect()->back()->with('success', 'Score successfully stored.');

    }

        public function PSM2DeleteScore($id){

        $this->authorize('delete psm2 score');

        return $this->gradingService->deleteScore($id);

    }

}
