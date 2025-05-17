<?php

namespace App\Services;

use App\Models\Rubric;
use App\Models\Criteria;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Illuminate\Support\Facades\Auth;

class RubricCriteriaService
{
    public function getPSM1ViewData()
    {
        $id = Auth::user()->id;
        $students = StudentPSM1::with(['score.rubric' => fn($q) => $q->withTrashed()])->get();
        $rubricDevelopment = $this->getRubrics('PSM1', 1, true);
        $rubricResearch = $this->getRubrics('PSM1', 2, true);

        return compact('students', 'rubricDevelopment', 'rubricResearch', 'id');
    }

    public function getPSM2ViewData()
    {
        $id = Auth::user()->id;
        $students = StudentPSM2::with(['score.rubric' => fn($q) => $q->withTrashed()])->get();
        $rubricDevelopment = $this->getRubrics('PSM2', 1, true);
        $rubricResearch = $this->getRubrics('PSM2', 2, true);

        return compact('students', 'rubricDevelopment', 'rubricResearch', 'id');
    }

    public function getRubrics($type, $rubricType, $withTrashed = false)
    {
        $query = Rubric::with('criteria')
            ->where('PSMType', $type)
            ->where('rubricType', $rubricType);

        return $withTrashed ? $query->withTrashed()->get() : $query->get();

    }

    public function getRubricsWithCriteria($type, $rubricType, $archived = false)
    {
        $query = Rubric::with('criteria')
            ->where('PSMType', $type)
            ->where('rubricType', $rubricType);

        return $archived ? $query->onlyTrashed()->get() : $query->get();
    }

    public function createRubric(array $data)
    {

        try {
            Rubric::create($data);
            return redirect()->back()->with('success', 'Rubric added successfully!');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to add rubric.');
        }
    }

    public function updateRubric($id, array $data)
    {

        try {
            $rubric = Rubric::withTrashed()->findOrFail($id);
            $rubric->update($data);
            return redirect()->back()->with('success', 'Rubric updated successfully!');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update rubric.');
        }
        
    }

    public function archiveRubric($id)
    {

        try {
            $rubric = Rubric::findOrFail($id);
            $rubric->isEnable = 0;
            $rubric->save();
    
            $rubric->delete(); // Soft delete
            return redirect()->back()->with('success', 'Rubric archived successfully.');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to archive rubric.');
        }

    }

    public function restoreRubric($id)
    {
        
        try {
            $rubric = Rubric::withTrashed()->findOrFail($id);
            $rubric->restore();
            return redirect()->back()->with('success', 'Rubric restored successfully.');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to restore rubric.');
        }
    }

    public function deleteRubric($id)
    {
        
        try {
            $rubric = Rubric::withTrashed()->findOrFail($id);
            $rubric->forceDelete();

            return redirect()->back()->with('success', 'Rubric deleted successfully.');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete rubric.');
        }
    }

    public function createCriteria(array $data)
    {
        try {
            Criteria::create($data);

            return redirect()->back()->with('success', 'Criteria added successfully!');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to create criteria.');
        }
    }

    public function updateCriteria($id, array $data)
    {

        try {
            $criteria = Criteria::findOrFail($id);
            $criteria->update($data);

            return redirect()->back()->with('success', 'Criteria updated successfully!');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update criteria.');
        }
    }

    public function deleteCriteria($id)
    {
        
        try {
            $criteria = Criteria::findOrFail($id);
            $criteria->delete();

            return redirect()->back()->with('success', 'Criteria deleted successfully.');

        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update criteria.');
        }
    }
}
