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
        $this->studentService->archiveStudent($id, "PSM1");
    }

    public function PSM1RestoreStudent($id)
    {
        $this->studentService->restoreStudent($id, "PSM1"); 
    }

    public function PSM1DeleteStudent($id)
    {
        $this->studentService->deleteStudent($id, "PSM1");
    }

    public function PSM1StoreStudent(Request $request, ProjectLecturerMergerService $service)
    {
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
        
        $validated['project_area_ai'] = $project_area_ai;

        $this->studentService->storeStudent($validated, "PSM1");

    }

    public function PSM1UpdateStudent(Request $request, $id)
    {
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

        $this->studentService->updateStudent( $request->all(),$id, "PSM1");
    }

    public function PSM1ImportStudent(Request $request)
    {
        $this->studentService->importPSM1Students($request->file('file'));
    }

    public function PSM1BulkArchiveStudent(Request $request){

        $this->studentService->bulkArchiveStudents($request->ids, "PSM1");
    }

    public function getStudentSample(){

        // $this->studentService->getStudentSample();

        $filePath = 'import_student_sample_data.xlsx'; // Update to CSV if needed
        
        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'Error cannot find the file');
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
        $this->studentService->archiveStudent($id, "PSM2");
    }

    public function PSM2RestoreStudent($id)
    {
        $this->studentService->restoreStudent($id, "PSM2"); 
    }

    public function PSM2DeleteStudent($id)
    {
        $this->studentService->deleteStudent($id, "PSM1");
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

        $this->studentService->storeStudent($validated, "PSM2");

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

        $this->studentService->updateStudent( $request->all(),$id, "PSM2");
    }

    public function PSM2ImportStudent(Request $request)
    {
        $this->studentService->importPSM2Students($request->file('file'));
    }

    public function PSM2BulkArchive(Request $request){

        $this->studentService->bulkArchiveStudents($request->ids, "PSM2");

    }



}
