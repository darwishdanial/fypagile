<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rubric;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Criteria;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Services\RubricCriteriaService;
use Illuminate\Support\Facades\Auth;

class RubricCriteriaController extends Controller
{

    protected RubricCriteriaService $service;

    public function __construct(RubricCriteriaService $service){

        $this->service = $service;

    }

    //VIEW RESULT
    //PSM1

    public function PSM1ViewResultCoordinator(){
        
        $this->authorize('view psm1 result table');

        $data = $this->service->getPSM1ViewData();

        return Inertia::render('Coordinator/PSM1/ViewResult', $data);
    }

    public function PSM1ViewResultPanel(){
        
        $userId = Auth::id();

        $students = StudentPSM1::with([
                'score' => function ($query) use ($userId) {
                    $query->where('panel_id', $userId)
                        ->with(['rubric' => fn($q) => $q->withTrashed()]);
                }
            ])
            ->where(function ($query) use ($userId) {
                $query->where('supervisorId', $userId)
                    ->orWhere('panelId', $userId)
                    ->orWhere('panel2Id', $userId);
            })
            ->get();

        $rubricDevelopment =  Rubric::where('PSMType', "PSM1")
                        ->where('rubricType', 1)->withTrashed()->get();
        $rubricResearch = Rubric::where('PSMType', "PSM1")
                        ->where('rubricType', 2)->withTrashed()->get();

        return Inertia::render('Panel/PSM1/ViewResult', [
            'students' => $students,
            'rubricDevelopment' => $rubricDevelopment,
            'rubricResearch' => $rubricResearch,
        ]);
    }



    //VIEW RESULT
    //PSM2

    public function PSM2ViewResultCoordinator(){

        $data = $this->service->getPSM2ViewData();

        return Inertia::render('Coordinator/PSM2/ViewResult', $data);
    }

    public function PSM2ViewResultPanel(){
        
        $userId = Auth::id();

        $students = StudentPSM2::with([
                'score' => function ($query) use ($userId) {
                    $query->where('panel_id', $userId)
                        ->with(['rubric' => fn($q) => $q->withTrashed()]);
                }
            ])
            ->where(function ($query) use ($userId) {
                $query->where('supervisorId', $userId)
                    ->orWhere('panelId', $userId)
                    ->orWhere('panel2Id', $userId);
            })
            ->get();

        $rubricDevelopment =  Rubric::where('PSMType', "PSM2")
                        ->where('rubricType', 1)->withTrashed()->get();
        $rubricResearch = Rubric::where('PSMType', "PSM2")
                        ->where('rubricType', 2)->withTrashed()->get();

        return Inertia::render('Panel/PSM2/ViewResult', [
            'students' => $students,
            'rubricDevelopment' => $rubricDevelopment,
            'rubricResearch' => $rubricResearch,
        ]);
    }

    //RUBRIC AND CRITERIA
    //PSM1

    public function PSM1DevelopmentRubric(){

        $rubricsDevelopmentActive = $this->service->getRubricsWithCriteria('PSM1', 1);

        $rubricsDevelopmentArchive = $this->service->getRubricsWithCriteria('PSM1', 1, true);

        return Inertia::render('Coordinator/PSM1/DevelopmentRubric',[
            'rubricsDevelopmentActive' => $rubricsDevelopmentActive,
            'rubricsDevelopmentArchive' => $rubricsDevelopmentArchive,
        ]);
    }

    public function PSM1ResearchRubric(){

        $this->authorize('view psm1 evaluation rubric');

        $rubricsResearchActive = $this->service->getRubricsWithCriteria('PSM1', 2);

        $rubricsResearchArchive = $this->service->getRubricsWithCriteria('PSM1', 2, true);

        return Inertia::render('Coordinator/PSM1/ResearchRubric',[
            'rubricsResearchActive' => $rubricsResearchActive,
            'rubricsResearchArchive' => $rubricsResearchArchive
        ]);
    }


    public function PSM1StoreEvaluationRubric(Request $request){

        // role = 1 = coordinator
        // role = 2 = panel
        // role = 3 = supervisor
       
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'total_weight' => 'required|integer|min:0|max:100',
            'session' => 'required|string',
            'isEnable' => 'required|boolean',
            'rubricType' => 'required|integer|min:1|max:3',
            'roleType' => 'required|integer|min:1|max:3',
            'progress' => 'required|string',
        ]);

        $this->service->createRubric($validated);

        return redirect()->back()->with('success', 'Rubric added successfully!');
    }

    public function PSM1StoreEvaluationCriteria(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        $this->service->createCriteria($validated);

        return redirect()->back()->with('success', 'Criteria added successfully!');
    }

    public function PSM1UpdateEvaluationRubric(Request $request, $id){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'session' => 'required|string',
            'total_weight' => 'required|integer|min:0|max:100',
            'isEnable' => 'required|boolean',
            'rubricType' => 'required|integer|min:1|max:3',
            'roleType' => 'required|integer|min:1|max:3',
            'progress' => 'required|string',
        ]);

        $this->service->updateRubric($id, $validated);

        return redirect()->back()->with('success', 'Rubric updated successfully!');
    }


    public function PSM1UpdateEvaluationCriteria(Request $request, $id){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        $this->service->updateCriteria($id, $validated);

        return redirect()->back()->with('success', 'Criteria updated successfully!');
    }

    public function PSM1ArchiveEvaluationRubric($id){

        $this->service->archiveRubric($id);

        return redirect()->back()->with('success', 'Rubric archived successfully.');
        
    }

    public function PSM1DeleteEvaluationRubric($id){

        $this->service->deleteRubric($id);

        return redirect()->back()->with('success', 'Rubric deleted successfully.');
    }

    public function PSM1RestoreEvaluationRubric($id){

        $this->service->restoreRubric($id);

        return redirect()->back()->with('success', 'Rubric restored successfully.');
    }

    public function PSM1DeleteEvaluationCriteria($id){

        $this->service->deleteCriteria($id);

        return redirect()->back()->with('success', 'Criteria deleted successfully.');
    }

    //RUBRIC AND CRITERIA
    //PSM2

    public function PSM2DevelopmentRubric(){

        $rubricsDevelopmentActive = $this->service->getRubricsWithCriteria('PSM2', 1);

        $rubricsDevelopmentArchive = $this->service->getRubricsWithCriteria('PSM2', 1, true);

        return Inertia::render('Coordinator/PSM2/DevelopmentRubric',[
            'rubricsDevelopmentActive' => $rubricsDevelopmentActive,
            'rubricsDevelopmentArchive' => $rubricsDevelopmentArchive,
        ]);
    }

    public function PSM2ResearchRubric(){

        $rubricsResearchActive = $this->service->getRubricsWithCriteria('PSM2', 2);

        $rubricsResearchArchive = $this->service->getRubricsWithCriteria('PSM2', 2, true);

        return Inertia::render('Coordinator/PSM2/ResearchRubric',[
            'rubricsResearchActive' => $rubricsResearchActive,
            'rubricsResearchArchive' => $rubricsResearchArchive
        ]);
    }


    public function PSM2StoreEvaluationRubric(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'total_weight' => 'required|integer|min:0|max:100',
            'session' => 'required|string',
            'isEnable' => 'required|boolean',
            'rubricType' => 'required|integer|min:1|max:3',
            'roleType' => 'required|integer|min:1|max:3',
            'progress' => 'required|string',
        ]);

        $this->service->createRubric($validated);

        return redirect()->back()->with('success', 'Rubric added successfully!');
    }

    public function PSM2StoreEvaluationCriteria(Request $request) {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        $this->service->createCriteria($validated);

        return redirect()->back()->with('success', 'Criteria added successfully!');
    }

    public function PSM2UpdateEvaluationRubric(Request $request, $id){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'session' => 'required|string',
            'total_weight' => 'required|integer|min:0|max:100',
            'isEnable' => 'required|boolean',
            'rubricType' => 'required|integer|min:1|max:3',
            'roleType' => 'required|integer|min:1|max:3',
            'progress' => 'required|string',
        ]);

        $this->service->updateRubric($id, $validated);

        return redirect()->back()->with('success', 'Rubric updated successfully!');
    }


    public function PSM2UpdateEvaluationCriteria(Request $request, $id){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        $this->service->updateCriteria($id, $validated);

        return redirect()->back()->with('success', 'Criteria updated successfully!');
    }

    public function PSM2ArchiveEvaluationRubric($id){

        $this->service->archiveRubric($id);

        return redirect()->back()->with('success', 'Rubric archived successfully.');
        
    }

    public function PSM2DeleteEvaluationRubric($id){

        $this->service->deleteRubric($id);

        return redirect()->back()->with('success', 'Rubric deleted successfully.');
    }

    public function PSM2RestoreEvaluationRubric($id){

        $this->service->restoreRubric($id);

        return redirect()->back()->with('success', 'Rubric restored successfully.');
    }

    public function PSM2DeleteEvaluationCriteria($id){

        $this->service->deleteCriteria($id);

        return redirect()->back()->with('success', 'Criteria deleted successfully.');
    }
}
