<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class StudentsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::select(
            "id", 
            "course", 
            "matric",
            "name",
            "email",
            "phone",
            "cohort",
            "sessionpsm"
        )->get();
    }

    public function headings(): array
    {
        return [
            "id", 
            "course", 
            "matric",
            "name",
            "email",
            "phone",
            "cohort",
            "sessionpsm"
        ];
    }
}
