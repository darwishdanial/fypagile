<?php

namespace App\Imports;
use App\Models\StudentPSM2;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;

class StudentsPSM2Import implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StudentPSM2([
            'course'     => $row['course'],
            'matric'    => $row['matric'], 
            'name'    => $row['name'], 
            'title'    => $row['title'], 
            'email'    => $row['email'], 
            'phone'    => $row['phone'], 
            'cohort'    => $row['cohort'], 
            'sessionpsm'    => $row['psmsession'], 
            'psm' => $row['psm']
        ]);
    }
}
