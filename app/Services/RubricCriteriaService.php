<?php

namespace App\Services;

use App\Models\Rubric;
use App\Models\Criteria;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;

class RubricCriteriaService
{
    public function getPSM1ViewData()
    {
        $students = StudentPSM1::with(['score.rubric' => fn($q) => $q->withTrashed()])->get();
        $rubricDevelopment = $this->getRubrics('PSM1', 1, true);
        $rubricResearch = $this->getRubrics('PSM1', 2, true);

        return compact('students', 'rubricDevelopment', 'rubricResearch');
    }

    public function getPSM2ViewData()
    {
        $students = StudentPSM2::with(['score.rubric' => fn($q) => $q->withTrashed()])->get();
        $rubricDevelopment = $this->getRubrics('PSM2', 1, true);
        $rubricResearch = $this->getRubrics('PSM2', 2, true);

        return compact('students', 'rubricDevelopment', 'rubricResearch');
    }

    public function getRubrics($type, $rubricType, $withTrashed = false)
    {
        $query = Rubric::where('PSMType', $type)
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
        return Rubric::create($data);
    }

    public function updateRubric($id, array $data)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);
        $rubric->update($data);
        return $rubric;
    }

    public function archiveRubric($id)
    {
        $rubric = Rubric::findOrFail($id);
        $rubric->isEnable = 0;
        $rubric->save();

        $rubric->delete(); // Soft delete
    }

    public function restoreRubric($id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);
        $rubric->restore();
    }

    public function deleteRubric($id)
    {
        $rubric = Rubric::withTrashed()->findOrFail($id);
        $rubric->forceDelete();
    }

    public function createCriteria(array $data)
    {
        return Criteria::create($data);
    }

    public function updateCriteria($id, array $data)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->update($data);
        return $criteria;
    }

    public function deleteCriteria($id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->delete();
    }
}
