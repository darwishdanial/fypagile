<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Services\StudentService;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use App\Imports\PSM1StudentsImport;
use App\Imports\PSM2StudentsImport;
use App\Services\ProjectLecturerMergerService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService){

        $this->studentService = $studentService;
    }

    //PSM1

    public function PSM1ListStudents()
    {
        $this->authorize('view psm1 list students table');

        $students = $this->studentService->getStudentPSM1();
        $archivedStudents = $this->studentService->getStudentPSM1Archive();

        return Inertia::render('Coordinator/PSM1/ListStudents',[
            'students' => $students,
            'archivedStudents' => $archivedStudents
        ]);
    }

    public function PSM1ArchiveStudent($id)
    {
        $student = StudentPSM1::findOrFail($id);
        $student->delete(); // Soft delete
        return redirect()->back()->with('success', 'Student archived successfully.');
    }

    public function PSM1RestoreStudent($id)
    {
        $student = StudentPSM1::onlyTrashed()->findOrFail($id);
        $student->restore(); // Restores the soft-deleted student
        return back()->with('success', 'Student restore successfully.');
    }

    public function PSM1DeleteStudent($id)
    {
        $student = StudentPSM1::onlyTrashed()->findOrFail($id);
        $student->forceDelete(); // Delete permanently
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    public function PSM1StoreStudent(Request $request, ProjectLecturerMergerService $service)
    {
        // dd( $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|unique:students_psm1,matric|max:50',
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students_psm1,email|max:255',
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        $project_area_ai = $service->matchCategory($validated['project_area']);
        
        // Add project_area_ai to validated data
        $validated['project_area_ai'] = $project_area_ai;

        StudentPSM1::create($validated);

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    public function PSM1UpdateStudent(Request $request, $id)
    {

        $student = StudentPSM1::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|max:50|unique:students_psm1,matric,' . $id,
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:students_psm1,email,' . $id,
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        $student->update([
            'name' => $request->name,
            'matric' => $request->matric,
            'course' => $request->course,
            'cohort' => $request->cohort,
            'phone' => $request->phone,
            'email' => $request->email,
            'project_type' => $request->project_type,
            'project_area' => $request->project_area,
            'title' => $request->title,
            'sessionpsm' => $request->sessionpsm,
        ]);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function PSM1ImportStudent(Request $request)
    {

        $import = new PSM1StudentsImport();
        Excel::import($import, $request->file('file'));

        $failures = Cache::get('psm1_import_failures', []);

        if($failures){
            Cache::forget('psm1_import_failures');
            //dd($failures);
            return redirect()->back()->with('warning', $failures);
        }

        return redirect()->back()->with('success', 'Student imported successfully!');
    }

    public function PSM1BulkArchiveStudent(Request $request){

        StudentPSM1::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', 'Selected students have been archived successfully!');
    }

    public function getStudentSample(){

        $filePath = 'import_student_sample_data.xlsx'; // Update to CSV if needed
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }
    
        return response()->download(storage_path("app/public/$filePath"));
    }

    //PSM2

    public function PSM2ListStudents()
    {
        $this->authorize('view psm2 list students table');

        $students = $this->studentService->getStudentPSM2();
        $archivedStudents =$this->studentService->getStudentPSM2Archive();

        return Inertia::render('Coordinator/PSM2/ListStudents',[
            'students' => $students,
            'archivedStudents' => $archivedStudents
        ]);
    }

    public function PSM2ArchiveStudent($id)
    {
        $student = StudentPSM2::findOrFail($id);
        $student->delete(); // Soft delete
        return redirect()->back()->with('success', 'Student archived successfully.');
    }

    public function PSM2RestoreStudent($id)
    {
        $student = StudentPSM2::onlyTrashed()->findOrFail($id);
        $student->restore(); // Restores the soft-deleted student
        return back()->with('success', 'Student restore successfully.');
    }

    public function PSM2DeleteStudent($id)
    {
        $student = StudentPSM2::onlyTrashed()->findOrFail($id);
        $student->forceDelete(); // Delete permanently
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }

    public function PSM2StoreStudent(Request $request, ProjectLecturerMergerService $service)
    {
        //dd( $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|unique:students_psm1,matric|max:50',
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students_psm1,email|max:255',
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        $project_area_ai = $service->matchCategory($validated['project_area']);
        
        // Add project_area_ai to validated data
        $validated['project_area_ai'] = $project_area_ai;

        StudentPSM2::create($validated);

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    public function PSM2UpdateStudent(Request $request, $id)
    {

        $student = StudentPSM2::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|max:50|unique:students_psm1,matric,' . $id,
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|max:255|unique:students_psm1,email,' . $id,
            'project_type' => ['required', Rule::in(['System Development', 'Research'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
        ]);

        $student->update([
            'name' => $request->name,
            'matric' => $request->matric,
            'course' => $request->course,
            'cohort' => $request->cohort,
            'phone' => $request->phone,
            'email' => $request->email,
            'project_type' => $request->project_type,
            'project_area' => $request->project_area,
            'title' => $request->title,
            'sessionpsm' => $request->sessionpsm,
        ]);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function PSM2ImportStudent(Request $request)
    {

        $import = new PSM2StudentsImport();
        Excel::import($import, $request->file('file'));

        $failures = Cache::get('psm2_import_failures', []);

        if($failures){
            Cache::forget('psm2_import_failures');
            //dd($failures);
            return redirect()->back()->with('warning', $failures);
        }

        return redirect()->back()->with('success', 'Student imported successfully!');
    }

    public function PSM2BulkArchive(Request $request){

        StudentPSM2::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', 'Selected students have been archived successfully!');
    }



}
