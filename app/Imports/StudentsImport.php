<?php

namespace App\Imports;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'course'     => $row['course'],
            'matric'    => $row['matric'], 
            'name'    => $row['name'], 
            'email'    => $row['email'], 
            'phone'    => $row['phone'], 
            'cohort'    => $row['cohort'], 
            'sessionpsm'    => $row['psmsession'], 
            'psm' => $row['psm'], 
        ]);
    }
}
