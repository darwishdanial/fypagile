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
        
        $this->authorize('view psm1 result coordinator table');

        $data = $this->service->getPSM1ViewData();

        return Inertia::render('Coordinator/PSM1/ViewResult', $data);
    }

    public function PSM1ViewResultPanel(){

        $this->authorize('view psm1 result panel table');
        
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

        $rubricDevelopment = Rubric::where('PSMType', 'PSM1')
            ->where('rubricType', 1)
            ->withTrashed()
            ->with('criteria')
            ->get();

        $rubricResearch = Rubric::where('PSMType', 'PSM1')
            ->where('rubricType', 2)
            ->withTrashed()
            ->with('criteria')
            ->get();

        return Inertia::render('Panel/PSM1/ViewResult', [
            'students' => $students,
            'rubricDevelopment' => $rubricDevelopment,
            'rubricResearch' => $rubricResearch,
            'id' => $userId
        ]);
    }



    //VIEW RESULT
    //PSM2

    public function PSM2ViewResultCoordinator(){

        $this->authorize('view psm2 result coordinator table');

        $data = $this->service->getPSM2ViewData();

        return Inertia::render('Coordinator/PSM2/ViewResult', $data);
    }

    public function PSM2ViewResultPanel(){

        $this->authorize('view psm2 result panel table');
        
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

        $rubricDevelopment = Rubric::where('PSMType', 'PSM2')
            ->where('rubricType', 1)
            ->withTrashed()
            ->with('criteria')
            ->get();

        $rubricResearch = Rubric::where('PSMType', 'PSM2')
            ->where('rubricType', 2)
            ->withTrashed()
            ->with('criteria')
            ->get();

        return Inertia::render('Panel/PSM2/ViewResult', [
            'students' => $students,
            'rubricDevelopment' => $rubricDevelopment,
            'rubricResearch' => $rubricResearch,
            'id' => $userId
        ]);
    }

    //RUBRIC AND CRITERIA
    //PSM1

    public function PSM1DevelopmentRubric(){

        $this->authorize('view psm1 development rubric table');

        $rubricsDevelopmentActive = $this->service->getRubricsWithCriteria('PSM1', 1);

        $rubricsDevelopmentArchive = $this->service->getRubricsWithCriteria('PSM1', 1, true);

        return Inertia::render('Coordinator/PSM1/DevelopmentRubric',[
            'rubricsDevelopmentActive' => $rubricsDevelopmentActive,
            'rubricsDevelopmentArchive' => $rubricsDevelopmentArchive,
        ]);
    }

    public function PSM1ResearchRubric(){

        $this->authorize('view psm1 research rubric');

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

        $this->authorize('store psm1 evaluation rubric');
       
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

        return $this->service->createRubric($validated);

    }

    public function PSM1StoreEvaluationCriteria(Request $request){

        $this->authorize('store psm1 evaluation criteria');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        return $this->service->createCriteria($validated);

    }

    public function PSM1UpdateEvaluationRubric(Request $request, $id){

        $this->authorize('update psm1 evaluation rubric');

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

        return $this->service->updateRubric($id, $validated);

    }


    public function PSM1UpdateEvaluationCriteria(Request $request, $id){

        $this->authorize('update psm1 evaluation criteria');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        return $this->service->updateCriteria($id, $validated);

    }

    public function PSM1ArchiveEvaluationRubric($id){

        $this->authorize('archive psm1 evaluation rubric');

        return $this->service->archiveRubric($id);
        
    }

    public function PSM1DeleteEvaluationRubric($id){

        $this->authorize('delete psm1 evaluation rubric');

        return $this->service->deleteRubric($id);

    }

    public function PSM1RestoreEvaluationRubric($id){

        $this->authorize('restore psm1 evaluation rubric');

        return $this->service->restoreRubric($id);

    }

    public function PSM1DeleteEvaluationCriteria($id){

        $this->authorize('delete psm1 evaluation criteria');

        return $this->service->deleteCriteria($id);

    }

    //RUBRIC AND CRITERIA
    //PSM2

    public function PSM2DevelopmentRubric(){

        $this->authorize('view psm2 development rubric table');

        $rubricsDevelopmentActive = $this->service->getRubricsWithCriteria('PSM2', 1);

        $rubricsDevelopmentArchive = $this->service->getRubricsWithCriteria('PSM2', 1, true);

        return Inertia::render('Coordinator/PSM2/DevelopmentRubric',[
            'rubricsDevelopmentActive' => $rubricsDevelopmentActive,
            'rubricsDevelopmentArchive' => $rubricsDevelopmentArchive,
        ]);
    }

    public function PSM2ResearchRubric(){

        $this->authorize('view psm2 research rubric');

        $rubricsResearchActive = $this->service->getRubricsWithCriteria('PSM2', 2);

        $rubricsResearchArchive = $this->service->getRubricsWithCriteria('PSM2', 2, true);

        return Inertia::render('Coordinator/PSM2/ResearchRubric',[
            'rubricsResearchActive' => $rubricsResearchActive,
            'rubricsResearchArchive' => $rubricsResearchArchive
        ]);
    }


    public function PSM2StoreEvaluationRubric(Request $request){

        $this->authorize('store psm2 evaluation rubric');

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

        return $this->service->createRubric($validated);

    }

    public function PSM2StoreEvaluationCriteria(Request $request) {

        $this->authorize('store psm2 evaluation criteria');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        return $this->service->createCriteria($validated);

    }

    public function PSM2UpdateEvaluationRubric(Request $request, $id){

        $this->authorize('update psm2 evaluation rubric');

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

        return $this->service->updateRubric($id, $validated);

    }


    public function PSM2UpdateEvaluationCriteria(Request $request, $id){

        $this->authorize('update psm1 evaluation criteria');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        return $this->service->updateCriteria($id, $validated);

    }

    public function PSM2ArchiveEvaluationRubric($id){

        $this->authorize('archive psm2 evaluation rubric');

        return $this->service->archiveRubric($id);
        
    }

    public function PSM2DeleteEvaluationRubric($id){

        $this->authorize('delete psm2 evaluation rubric');

        return $this->service->deleteRubric($id);

    }

    public function PSM2RestoreEvaluationRubric($id){

        $this->authorize('restore psm2 evaluation rubric');

        return $this->service->restoreRubric($id);

    }

    public function PSM2DeleteEvaluationCriteria($id){

        $this->authorize('delete psm2 evaluation criteria');

        return $this->service->deleteCriteria($id);

    }
}
