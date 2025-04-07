<?php

namespace App\Services;

use App\Models\Rubric;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\User;
use App\Models\Score;

class GradingService
{
    public function getRubrics(string $psmType, int $rubricType, int $roleType)
    {
        return Rubric::with('criteria')
            ->where('PSMType', $psmType)
            ->where('rubricType', $rubricType)
            ->where('roleType', $roleType)
            ->where('isEnable', true)
            ->get();
    }

    public function getRubricsCoordinator(string $psmType)
    {
        return Rubric::with('criteria')
            ->where('PSMType', $psmType)
            ->where('roleType', 1)
            ->where('isEnable', true)
            ->get();
    }

    public function getAllStudents(string $psmType)
    {
        return $psmType === 'PSM1' ? StudentPSM1::all() : StudentPSM2::all();
    }

    public function storeScore(array $requestData, string $psmType)
    {
        $totalScore = array_sum($requestData['criteria']);
        $weight = $requestData['total_weight'];
        $finalScore = $weight / 100 * $totalScore;

        $panelName = User::findOrFail($requestData['panel_id'])->name;

        $attributes = [
            'rubric_id' => $requestData['rubric_id'],
            'panel_id' => $requestData['panel_id'],
        ];

        if ($psmType === 'PSM1') {
            $attributes['student_psm1_id'] = $requestData['student_id'];
        } else {
            $attributes['student_psm2_id'] = $requestData['student_id'];
        }

        Score::updateOrCreate(
            $attributes,
            [
                'mark' => $finalScore,
                'comment' => $requestData['comments'],
                'panel_name' => $panelName,
            ]
        );
    }
}
