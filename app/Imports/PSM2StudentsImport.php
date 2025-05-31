<?php
namespace App\Imports;

use App\Models\StudentPSM2;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Cache;
use App\Services\ProjectLecturerMergerService;


class PSM2StudentsImport implements ToModel, WithValidation, SkipsOnFailure, WithHeadingRow
{
    use SkipsFailures;
    protected $mergerService;

    public function __construct(ProjectLecturerMergerService $mergerService)
    {
        $this->mergerService = $mergerService;
    }

    public function prepareForValidation($data)
    {
        if (isset($data['project_type'])) {
            $data['project_type'] = ucwords(strtolower($data['project_type']));
        }
        return $data;
    }


    public function model(array $row)
    {
        return new StudentPSM2([
            'name'          => $row['name'],
            'matric'        => $row['matric'],
            'course'        => $row['course'],
            'title'         => $row['title'],
            'project_area'  => $row['project_area'],
            'project_area_ai'  => $this->mergerService->matchCategory($row['project_area']),
            'project_type'  => $row['project_type'],
            'sessionpsm'    => $row['sessionpsm'],
            'cohort'        => $row['cohort'],
            'phone'         => $row['phone'],
            'email'         => $row['email'],
            'sagile_link'   => $row['sagile_link'],
            'github_link'   => $row['github_link'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'matric'        => 'required|string|unique:students_psm2,matric|max:50',
            'course'        => 'required|string|max:255',
            'title'         => 'required|string|max:255',
            'project_area'  => 'required|string|max:255',
            'project_type'  => ['required', Rule::in(['System Development', 'Research Based'])],
            'sessionpsm'    => 'required|string|max:50',
            'cohort'        => 'required|string|max:50',
            'phone'         => 'required|string|max:20',
            'email'         => 'required|unique:students_psm2,email|max:255',
            'sagile_link'   => 'required|string',
            'github_link'   => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name cannot exceed 255 characters.',
        
            'matric.required' => 'Matric is required.',
            'matric.string' => 'Matric must be a valid string.',
            'matric.unique' => 'Matric number already exists.',
            'matric.max' => 'Matric cannot exceed 50 characters.',
        
            'course.required' => 'Course is required.',
            'course.string' => 'Course must be a valid string.',
            'course.max' => 'Course cannot exceed 255 characters.',
        
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a valid string.',
            'title.max' => 'Title cannot exceed 255 characters.',
        
            'project_area.required' => 'Project Area is required.',
            'project_area.string' => 'Project Area must be a valid string.',
            'project_area.max' => 'Project Area cannot exceed 255 characters.',
        
            'project_type.required' => 'Project Type is required.',
            'project_type.in' => 'Project Type must be either System Development or Research.',
        
            'sessionpsm.required' => 'Session PSM is required.',
            'sessionpsm.string' => 'Session PSM must be a valid string.',
            'sessionpsm.max' => 'Session PSM cannot exceed 50 characters.',
        
            'cohort.required' => 'Cohort is required.',
            'cohort.string' => 'Cohort must be a valid string.',
            'cohort.max' => 'Cohort cannot exceed 50 characters.',
        
            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a valid string.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
        
            'email.required' => 'Email is required.',
            'email.unique' => 'Email already exists.',
            'email.max' => 'Email cannot exceed 255 characters.',

            'sagile_link.required' => 'SAgile link is required.',
            'sagile_link.string' => 'SAgile link must be a valid string.',

            'github_link.required' => 'SAgile link is required.',
            'github_link.string' => 'SAgile link must be a valid string.',
        ];        
    }

    public function onFailure(Failure ...$failures)
    {
        // Store failures in cache
        $existing = Cache::get('PSM2_import_failures', []);
        Cache::put('PSM2_import_failures', array_merge($existing, $failures));
    }

}
