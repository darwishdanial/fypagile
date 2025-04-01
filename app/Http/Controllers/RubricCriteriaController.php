<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rubric;
use Inertia\Inertia;
use App\Models\Criteria;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;

class RubricCriteriaController extends Controller
{
    //VIEW RESULT
    //PSM1

    public function PSM1ViewResult()
    {
        $this->authorize('view psm1 result table');

        $students = StudentPSM1::with(['score.rubric' => function ($query) {
            $query->withTrashed(); // Include soft-deleted rubrics
        }])->get();

        //dd($students[0]);

        $rubricDevelopment = Rubric:: where('PSMType',  'PSM1')
            ->where('isDevelopment',  true)->withTrashed()->get();

        $rubricResearch = Rubric:: where('PSMType',  'PSM1')
            ->where('isResearch',  true)->withTrashed()->get();

        return Inertia::render('Coordinator/PSM1/ViewResult',[
            'students' => $students,
            'rubricDevelopment' => $rubricDevelopment,
            'rubricResearch' => $rubricResearch,
        ]);
    }

    //VIEW RESULT
    //PSM2

    public function PSM2ViewResult()
    {

        $students = StudentPSM2::with(['score.rubric' => function ($query) {
            $query->withTrashed(); // Include soft-deleted rubrics
        }])->get();

        // dd($students[0]);

        $rubricDevelopment = Rubric:: where('PSMType',  'PSM2')
            ->where('isDevelopment',  true)->withTrashed()->get();

        $rubricResearch = Rubric:: where('PSMType',  'PSM2')
            ->where('isResearch',  true)->withTrashed()->get();

        return Inertia::render('Coordinator/PSM2/ViewResult',[
            'students' => $students,
            'rubricDevelopment' => $rubricDevelopment,
            'rubricResearch' => $rubricResearch,
        ]);
    }

    //RUBRIC AND CRITERIA
    //PSM1

    public function PSM1DevelopmentRurbric()
    {
        $rubricsDevelopmentActive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM1')
                    ->where('isDevelopment',  true)
                    ->get();

        $rubricsDevelopmentArchive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM1')
                    ->where('isDevelopment',  true)
                    ->onlyTrashed()
                    ->get();

        return Inertia::render('Coordinator/PSM1/DevelopmentRubric',[
            'rubricsDevelopmentActive' => $rubricsDevelopmentActive,
            'rubricsDevelopmentArchive' => $rubricsDevelopmentArchive,
        ]);
    }

    public function PSM1ResearchRurbric()
    {
        $this->authorize('view psm1 evaluation rubric');

        $rubricsResearchActive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM1')
                    ->where('isResearch', true)
                    ->get();

        $rubricsResearchArchive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM1')
                    ->where('isResearch',  true)
                    ->onlyTrashed()
                    ->get();

        return Inertia::render('Coordinator/PSM1/ResearchRubric',[
            'rubricsResearchActive' => $rubricsResearchActive,
            'rubricsResearchArchive' => $rubricsResearchArchive
        ]);
    }


    public function PSM1StoreEvaluationRurbric(Request $request)
    {
        //dd( $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'total_weight' => 'required|integer|min:0|max:100',
            'session' => 'required|string',
            'isEnable' => 'required|boolean',
            'isResearch' => 'required|boolean',
            'isDevelopment' => 'required|boolean',
            'isCoordinatorPSM1' => 'required|boolean',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'isCoordinatorPSM2' => 'required|boolean',
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
        ]);

        Rubric::create($validated);

        return redirect()->back()->with('success', 'Rubric added successfully!');
    }

    public function PSM1StoreEvaluationCriteria(Request $request)
    {
        //dd( $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        Criteria::create($validated);

        return redirect()->back()->with('success', 'Criteria added successfully!');
    }

    public function PSM1UpdateEvaluationRurbric(Request $request, $id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);

        //dd($rubric);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'session' => 'required|string',
            'total_weight' => 'required|integer|min:0|max:100',
            'isEnable' => 'required|boolean',
            'isResearch' => 'required|boolean',
            'isDevelopment' => 'required|boolean',
            'isCoordinatorPSM1' => 'required|boolean',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
        ]);

        $rubric->update($validated);

        return redirect()->back()->with('success', 'Rubric updated successfully!');
    }


    public function PSM1UpdateEvaluationCriteria(Request $request, $id)
    {
        //dd( $request->all());

        $criteria = Criteria::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        $criteria->update($validated);

        return redirect()->back()->with('success', 'Criteria updated successfully!');
    }

    public function PSM1ArchiveEvaluationRurbric($id)
    {
        $rubric = Rubric::findOrFail($id);

        $rubric->delete();

        return redirect()->back()->with('success', 'Rubric archived successfully.');
        
    }

    public function PSM1DeleteEvaluationRurbric($id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);

        $rubric->forceDelete();

        return redirect()->back()->with('success', 'Rubric deleted successfully.');
    }
    public function PSM1RestoreEvaluationRurbric($id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);

        $rubric->restore();

        return redirect()->back()->with('success', 'Rubric restored successfully.');
    }

    public function PSM1DeleteEvaluationCriteria($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete(); // Soft delete
        return redirect()->back()->with('success', 'Criteria deleted successfully.');
    }

    //RUBRIC AND CRITERIA
    //PSM2

    public function PSM2DevelopmentRurbric()
    {
        $rubricsDevelopmentActive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM2')
                    ->where('isDevelopment',  true)
                    ->get();

        $rubricsDevelopmentArchive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM2')
                    ->where('isDevelopment',  true)
                    ->onlyTrashed()
                    ->get();

        return Inertia::render('Coordinator/PSM2/DevelopmentRubric',[
            'rubricsDevelopmentActive' => $rubricsDevelopmentActive,
            'rubricsDevelopmentArchive' => $rubricsDevelopmentArchive,
        ]);
    }

    public function PSM2ResearchRurbric()
    {

        $rubricsResearchActive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM2')
                    ->where('isResearch', true)
                    ->get();

        $rubricsResearchArchive = Rubric::with(['criteria'])  // Only load criteria, not grading levels
                    ->where('PSMType',  'PSM2')
                    ->where('isResearch',  true)
                    ->onlyTrashed()
                    ->get();

        return Inertia::render('Coordinator/PSM2/ResearchRubric',[
            'rubricsResearchActive' => $rubricsResearchActive,
            'rubricsResearchArchive' => $rubricsResearchArchive
        ]);
    }


    public function PSM2StoreEvaluationRurbric(Request $request)
    {
        //dd( $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'total_weight' => 'required|integer|min:0|max:100',
            'session' => 'required|string',
            'isEnable' => 'required|boolean',
            'isResearch' => 'required|boolean',
            'isDevelopment' => 'required|boolean',
            'isCoordinatorPSM1' => 'required|boolean',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
            'isCoordinatorPSM2' => 'required|boolean',
            'isSupervisorPSM2' => 'required|boolean',
            'isPanelPSM2' => 'required|boolean',
        ]);

        Rubric::create($validated);

        return redirect()->back()->with('success', 'Rubric added successfully!');
    }

    public function PSM2StoreEvaluationCriteria(Request $request)
    {
        //dd( $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        Criteria::create($validated);

        return redirect()->back()->with('success', 'Criteria added successfully!');
    }

    public function PSM2UpdateEvaluationRurbric(Request $request, $id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);

        //dd($rubric);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'PSMType' => 'required|string|max:255',
            'session' => 'required|string',
            'total_weight' => 'required|integer|min:0|max:100',
            'isEnable' => 'required|boolean',
            'isResearch' => 'required|boolean',
            'isDevelopment' => 'required|boolean',
            'isCoordinatorPSM1' => 'required|boolean',
            'isSupervisorPSM1' => 'required|boolean',
            'isPanelPSM1' => 'required|boolean',
        ]);

        $rubric->update($validated);

        return redirect()->back()->with('success', 'Rubric updated successfully!');
    }


    public function PSM2UpdateEvaluationCriteria(Request $request, $id)
    {
        //dd( $request->all());

        $criteria = Criteria::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rubric_id' => 'required|integer|exists:rubrics,id',
            'weight' => 'required|decimal:0,2|max:100',
        ]);

        $criteria->update($validated);

        return redirect()->back()->with('success', 'Criteria updated successfully!');
    }

    public function PSM2ArchiveEvaluationRurbric($id)
    {
        $rubric = Rubric::findOrFail($id);

        $rubric->delete();

        return redirect()->back()->with('success', 'Rubric archived successfully.');
        
    }

    public function PSM2DeleteEvaluationRurbric($id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);

        $rubric->forceDelete();

        return redirect()->back()->with('success', 'Rubric deleted successfully.');
    }
    public function PSM2RestoreEvaluationRurbric($id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);

        $rubric->restore();

        return redirect()->back()->with('success', 'Rubric restored successfully.');
    }

    public function PSM2DeleteEvaluationCriteria($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete(); // Soft delete
        return redirect()->back()->with('success', 'Criteria deleted successfully.');
    }
}
