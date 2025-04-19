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
            'project_area',
            'project_type',
            'panel_name',
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
            $aiData->panel_name,
        ];
    }
}
