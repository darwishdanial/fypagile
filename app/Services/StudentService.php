<?php

namespace App\Services;

use App\Models\StudentPSM2;
use App\Models\StudentPSM1;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PSM1StudentsImport;
use App\Imports\PSM2StudentsImport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StudentService 
{
    public function getStudents(string $studentType)
    {
        if ($studentType === 'PSM1') {
            $model = StudentPSM1::query();
            $table = 'students_psm1';
        } elseif ($studentType === 'PSM2') {
            $model = StudentPSM2::query();
            $table = 'students_psm2';
        } else {
            return collect(); // Invalid student type
        }

        $students = $model
            ->leftJoin('users as sv', "$table.supervisorId", '=', 'sv.id')
            ->leftJoin('users as panel_users', "$table.panelId", '=', 'panel_users.id')
            ->leftJoin('users as panel2_users', "$table.panel2Id", '=', 'panel2_users.id')
            ->select(
                "$table.id",
                "$table.name",
                "$table.course",
                "$table.matric",
                "$table.title",
                "$table.project_area",
                "$table.project_area_ai",
                "$table.project_type",
                "$table.sessionpsm",
                "$table.cohort",
                "$table.phone",
                "$table.email",
                'sv.name as sv_name',
                'panel_users.name as panel_name',
                'panel2_users.name as panel2_name'
            )
            ->get();

        return $students;
    }

    public function getStudentsArchive(string $studentType)
    {
        if ($studentType === 'PSM1') {
            $model = StudentPSM1::onlyTrashed();
            $table = 'students_psm1';
        } elseif ($studentType === 'PSM2') {
            $model = StudentPSM2::onlyTrashed();
            $table = 'students_psm2';
        } else {
            return collect(); // Return empty collection if invalid type
        }

        $archivedStudents = $model
            ->leftJoin('users as sv', "$table.supervisorId", '=', 'sv.id')
            ->leftJoin('users as panel_users', "$table.panelId", '=', 'panel_users.id')
            ->leftJoin('users as panel2_users', "$table.panel2Id", '=', 'panel2_users.id')
            ->select(
                "$table.id",
                "$table.name",
                "$table.course",
                "$table.matric",
                "$table.title",
                "$table.project_area",
                "$table.project_area_ai",
                "$table.project_type",
                "$table.sessionpsm",
                "$table.cohort",
                "$table.phone",
                "$table.email",
                'sv.name as sv_name',
                'panel_users.name as panel_name',
                'panel2_users.name as panel2_name',
                "$table.deleted_at"
            )
            ->get();

        return $archivedStudents;
    }

    public function getStudentsSupervisor(string $studentType, ?int $supervisorId)
    {
        if ($studentType === 'PSM1') {
            $model = new StudentPSM1();
        } elseif ($studentType === 'PSM2') {
            $model = new StudentPSM2();
        } else {
            return collect(); // Return empty collection if invalid type
        }

        $unassignedStudents = $model->newQuery()
            ->whereNull('supervisorId')
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panel2Id')
                    ->orWhere('panel2Id', '!=', $supervisorId);
            })
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panelId')
                    ->orWhere('panelId', '!=', $supervisorId);
            })
            ->get(['id','name','title','project_area','project_type','supervisorId'])
            ->map(function ($student) {
                $student->assigned = false;
                return $student;
            });

        $assignedStudents = $model->newQuery()
            ->where('supervisorId', $supervisorId)
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panel2Id')
                    ->orWhere('panel2Id', '!=', $supervisorId);
            })
            ->where(function($query) use ($supervisorId) {
                $query->whereNull('panelId')
                    ->orWhere('panelId', '!=', $supervisorId);
            })
            ->get(['id','name','title','project_area','project_type','supervisorId'])
            ->map(function ($student) {
                $student->assigned = true;
                return $student;
            });

        $students = $assignedStudents->merge($unassignedStudents);

        return $students;
    }

    public function assignStudentsSupervisor(string $studentType, int $studentId, int $supervisorId)
    {
        if ($studentType === 'PSM1') {
            $model = new StudentPSM1();
        } elseif ($studentType === 'PSM2') {
            $model = new StudentPSM2();
        } else {
            return false; // or throw an exception if you prefer
        }

        return $model->newQuery()
            ->whereId($studentId)
            ->update(['supervisorId' => $supervisorId]);
    }

    public function unassignStudentsSupervisor(string $studentType, int $studentId)
    {
        if ($studentType === 'PSM1') {
            $model = new StudentPSM1();
        } elseif ($studentType === 'PSM2') {
            $model = new StudentPSM2();
        } else {
            return false; // or throw an exception if needed
        }

        return $model->newQuery()
            ->whereId($studentId)
            ->update(['supervisorId' => null]);
    }

    public function getStudentsPanel(string $studentType, ?int $panelId, int $type)
    {
        if ($studentType === 'PSM1') {
            $model = new StudentPSM1();
        } elseif ($studentType === 'PSM2') {
            $model = new StudentPSM2();
        } else {
            return false; // or throw exception
        }

        $assignedStudents = ($type == 1)
            ? $model->newQuery()->where('panelId', $panelId)
                ->get(['id','name','title','project_area','project_type','panelId', 'panelId_ai'])
                ->map(function ($student) {
                    $student->assigned = true;
                    return $student;
                })
            : $model->newQuery()->where('panel2Id', $panelId)
                ->get(['id','name','title','project_area','project_type','panel2Id', 'panel2Id_ai'])
                ->map(function ($student) {
                    $student->assigned = true;
                    return $student;
                });

        $unassignedStudents = ($type == 1)
            ? $model->newQuery()
                ->whereNull('panelId')
                ->where(function($query) use ($panelId) {
                    $query->whereNull('panel2Id')->orWhere('panel2Id', '!=', $panelId);
                })
                ->where(function($query) use ($panelId) {
                    $query->whereNull('supervisorId')->orWhere('supervisorId', '!=', $panelId);
                })
                ->get(['id','name','title','project_area','project_type','panelId','panelId_ai','project_area_ai'])
            : $model->newQuery()
                ->whereNull('panel2Id')
                ->where(function($query) use ($panelId) {
                    $query->whereNull('panelId')->orWhere('panelId', '!=', $panelId);
                })
                ->where(function($query) use ($panelId) {
                    $query->whereNull('supervisorId')->orWhere('supervisorId', '!=', $panelId);
                })
                ->get(['id','name','title','project_area','project_type','panel2Id','panel2Id_ai','project_area_ai']);

        return $assignedStudents->merge($unassignedStudents);
    }

    public function assignStudentsPanel1(string $studentType, $studentId, $panelId)
    {
        if ($studentType === 'PSM1') {
            StudentPSM1::whereId($studentId)->update(['panelId' => $panelId]);
        } elseif ($studentType === 'PSM2') {
            StudentPSM2::whereId($studentId)->update(['panelId' => $panelId]);
        } else {
            return false; // or throw exception
        }
    }

    public function assignStudentsPanel2(string $studentType, $studentId, $panelId)
    {
        if ($studentType === 'PSM1') {
            StudentPSM1::whereId($studentId)->update(['panel2Id' => $panelId]);
        } elseif ($studentType === 'PSM2') {
            StudentPSM2::whereId($studentId)->update(['panel2Id' => $panelId]);
        } else {
            return false; // or throw exception
        }
    }

    public function unassignStudentsPanel1(string $studentType, $studentId)
    {
        if ($studentType === 'PSM1') {
            StudentPSM1::whereId($studentId)->update(['panelId' => null]);
        } elseif ($studentType === 'PSM2') {
            StudentPSM2::whereId($studentId)->update(['panelId' => null]);
        } else {
            return false; // or throw an exception
        }
    }

    public function unassignStudentsPanel2(string $studentType, $studentId)
    {
        if ($studentType === 'PSM1') {
            StudentPSM1::whereId($studentId)->update(['panel2Id' => null]);
        } elseif ($studentType === 'PSM2') {
            StudentPSM2::whereId($studentId)->update(['panel2Id' => null]);
        } else {
            return false; // or throw an exception if student type is invalid
        }
    }


    public function getStudentsSupervisorGrade(string $studentType, ?int $supervisorId, int $type)
    {
        $model = $studentType === 'PSM1' ? StudentPSM1::class : StudentPSM2::class;

        $projectType = $type == 1 ? 'System Development' : 'Research Based';

        return $model::where('supervisorId', $supervisorId)
                    ->where('project_type', $projectType)
                    ->get();
    }

    public function getStudentsPanelGrade(string $studentType, ?int $panelId, int $type)
    {
        $model = $studentType === 'PSM1' ? StudentPSM1::class : StudentPSM2::class;

        $projectType = $type == 1 ? 'System Development' : 'Research Based';

        return $model::where(function ($query) use ($panelId) {
                    $query->where('panelId', $panelId)
                        ->orWhere('panel2Id', $panelId);
                })
                ->where('project_type', $projectType)
                ->get();
    }

    public function importStudents(string $studentType, $file)
    {
        try {
            $mergerService = app(ProjectLecturerMergerService::class); 
            $importClass = $studentType === 'PSM1' 
                ? new PSM1StudentsImport($mergerService) 
                : new PSM2StudentsImport($mergerService);
            Excel::import($importClass, $file);

            $failures = Cache::get("{$studentType}_import_failures", []);

            if ($failures) {
                Cache::forget("{$studentType}_import_failures");
                return redirect()->back()->with('warning', $failures);
            }

            return redirect()->back()->with('success', 'Students imported successfully!');
        } catch (\Exception $e) {
            logger("Error importing {$studentType} students: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing students.');
        }
    }


    public function archiveStudent($id, string $studentType)
    {
        try{
            $student = ($studentType === 'PSM1') ? StudentPSM1::findOrFail($id) : StudentPSM2::findOrFail($id);
            $student->delete(); // Soft delete
            return redirect()->back()->with('success', 'Student archived successfully.');
        }catch(\Exception $e){
            logger($e->getMessage());   
            return redirect()->back()->with('error', 'Failed to archive student.');
        }

    }

    public function restoreStudent($id, string $studentType)
    {
        try{
            $student = ($studentType === 'PSM1') ? StudentPSM1::withTrashed()->findOrFail($id) : StudentPSM2::withTrashed()->findOrFail($id);
            $student->restore(); // Restores the soft-deleted student
            return back()->with('success', 'Student restore successfully.');
        }catch(\Exception $e){
            logger($e->getMessage());   
            return redirect()->back()->with('error', 'Failed to restore student.');
        }
        
    }

    public function deleteStudent($id, string $studentType)
    {
        try{

            $student = ($studentType === 'PSM1') ? StudentPSM1::onlyTrashed()->findOrFail($id) : StudentPSM2::onlyTrashed()->findOrFail($id);
            $student->forceDelete(); // Delete permanently
            return redirect()->back()->with('success', 'Student deleted successfully.');
        }catch(\Exception $e){
            logger($e->getMessage());   
            return redirect()->back()->with('error', 'Failed to delete student.');
        }
        
    }

    public function storeStudent(array $data, string $studentType)
    {
        try {

            $studentType === 'PSM1' ? StudentPSM1::create($data) : StudentPSM2::create($data);

            return redirect()->back()->with('success', 'Student added successfully!');
        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('error', 'Failed to store student.');
        }
    }

    public function updateStudent(array $data, $id, string $studentType)
    {
        try{

            $student = ($studentType === 'PSM1') ? StudentPSM1::findOrFail($id) : StudentPSM2::findOrFail($id);

            $student->update($data);
    
            return redirect()->back()->with('success', 'Student updated successfully!');
        }catch(\Exception $e){
            logger($e->getMessage());   
            return redirect()->back()->with('error', 'Failed to update student.');
        }

    }

    public function bulkArchiveStudents(array $studentIds, string $studentType)
    {
        try {
            $studentType === 'PSM1' ? StudentPSM1::whereIn('id', $studentIds)->delete() : StudentPSM2::whereIn('id', $studentIds)->delete();
            return redirect()->back()->with('success', 'Selected students have been archived successfully!');
        } catch (\Exception $e) {
            logger('Error archiving PSM1 students: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error archiving students');
        }
    }


}