<?php

namespace App\Exports;

use App\Models\AIData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AiDataExport implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
    {
        return AIData::query();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Project Area',
            'Project Type',
            'Examiner Status',
            'Panel Name',
        ];
    }

    /**
     * @param AIData $aiData
     * @return array
     */
    public function map($aiData): array
    {
        return [
            $aiData->project_area,
            $aiData->project_type,
            $aiData->examiner_status,
            $aiData->panel_name,
        ];
    }
}
