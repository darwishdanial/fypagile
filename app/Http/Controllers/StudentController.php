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
use Illuminate\Support\Facades\Auth;

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
       
        $students = $this->studentService->getStudents("PSM1");
        $archivedStudents = $this->studentService->getStudentsArchive("PSM1");

        // dd($students[0]);

        return Inertia::render('Coordinator/PSM1/ListStudents',[
            'students' => $students,
            'archivedStudents' => $archivedStudents
        ]);
    }

    public function PSM1ArchiveStudent($id)
    {
        $this->authorize('archive psm1 students');

        return $this->studentService->archiveStudent($id, "PSM1");

    }

    public function PSM1RestoreStudent($id)
    {
        $this->authorize('restore psm1 students');

        return $this->studentService->restoreStudent($id, "PSM1"); 

    }

    public function PSM1DeleteStudent($id)
    {
        $this->authorize('delete psm1 students');

        return $this->studentService->deleteStudent($id, "PSM1");

    }

    public function PSM1StoreStudent(Request $request, ProjectLecturerMergerService $service)
    {
        $this->authorize('store psm1 students');


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|unique:students_psm1,matric|max:50',
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students_psm1,email|max:255',
            'project_type' => ['required', Rule::in(['System Development', 'Research Based'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
            'sagile_link' => 'required|string',
        ]);

        $project_area_ai = $service->matchCategory($validated['project_area']);
        
        $validated['project_area_ai'] = $project_area_ai;

        return $this->studentService->storeStudent($validated, "PSM1");

    }

    public function PSM1UpdateStudent(Request $request, $id)
    {

        $this->authorize('update psm1 students');

        $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|max:50|unique:students_psm1,matric,' . $id,
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:students_psm1,email,' . $id,
            'project_type' => ['required', Rule::in(['System Development', 'Research Based'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
            'sagile_link' => 'required|string',
        ]);

        return $this->studentService->updateStudent( $request->all(),$id, "PSM1");

    }

    public function PSM1ImportStudent(Request $request)
    {
        $this->authorize('import psm1 students');

        return $this->studentService->importStudents("PSM1",$request->file('file'));

    }

    public function PSM1BulkArchiveStudent(Request $request){

        $this->authorize('bulk archive psm1 students');

        return $this->studentService->bulkArchiveStudents($request->ids, "PSM1");

    }

    public function getStudentSamplePSM1(){

        // $this->studentService->getStudentSample();

        $filePath = 'import_student_sample_data_PSM1.xlsx'; // Update to CSV if needed
        
        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'Error cannot find the file');
        }
    
        return response()->download(storage_path("app/public/$filePath"));
    }

    public function PSM1StudentRequest(){

        $this->authorize('view psm1 supervisor request');

        $id = Auth::user()->id;

        $studentsDevelopment = $this->studentService->getStudentRequestSupervisor("PSM1",$id, 1);

        $studentResearch = $this->studentService->getStudentRequestSupervisor("PSM1",$id, 2);

        $userRole = Auth::user()->role === 1 ? 'Coordinator' : 'Panel';

        if ($userRole === 'Coordinator') {
            return Inertia::render('Coordinator/PSM1/StudentRequest', [
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            ]);
        } else {
            return Inertia::render('Panel/PSM1/StudentRequest', [
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            ]);
        }
    }

    public function PSM1SAcceptSupervisor($id)
    {
        $this->authorize('accept psm1 supervisor');

        $studentId = $id;
        $supervisorId = Auth::user()->id;

        $this->studentService->acceptStudentsSupervisor("PSM1", $studentId, $supervisorId);
    }

    public function PSM1RejectSupervisor($id)
    {
        $this->authorize('reject psm1 supervisor');

        $studentId = $id;

        return $this->studentService->rejectStudentsSupervisor("PSM1", $studentId);
    }

    //PSM2

    public function PSM2ListStudents()
    {
        $this->authorize('view psm2 list students table');

        $students = $this->studentService->getStudents("PSM2");
        $archivedStudents =$this->studentService->getStudentsArchive("PSM2");

        return Inertia::render('Coordinator/PSM2/ListStudents',[
            'students' => $students,
            'archivedStudents' => $archivedStudents
        ]);
    }

    public function PSM2ArchiveStudent($id)
    {
        $this->authorize('archive psm2 students');

        return $this->studentService->archiveStudent($id, "PSM2");

    }

    public function PSM2RestoreStudent($id)
    {
        $this->authorize('restore psm2 students');

        return $this->studentService->restoreStudent($id, "PSM2"); 

    }

    public function PSM2DeleteStudent($id)
    {
        $this->authorize('delete psm2 students');

        return $this->studentService->deleteStudent($id, "PSM2");

    }

    public function PSM2StoreStudent(Request $request, ProjectLecturerMergerService $service)
    {

        $this->authorize('store psm2 students');

        //dd( $request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|unique:students_psm1,matric|max:50',
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:students_psm1,email|max:255',
            'project_type' => ['required', Rule::in(['System Development', 'Research Based'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
            'sagile_link' => 'required|string',
            'github_link' => 'required|string',
        ]);

        $project_area_ai = $service->matchCategory($validated['project_area']);
        
        // Add project_area_ai to validated data
        $validated['project_area_ai'] = $project_area_ai;

        return $this->studentService->storeStudent($validated, "PSM2");

    }

    public function PSM2UpdateStudent(Request $request, $id)
    {

        $this->authorize('update psm2 students');

        // $student = StudentPSM2::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'matric' => 'required|string|max:50|unique:students_psm1,matric,' . $id,
            'course' => 'required|string|max:255',
            'cohort' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'email' => 'required|max:255|unique:students_psm1,email,' . $id,
            'project_type' => ['required', Rule::in(['System Development', 'Research Based'])],
            'project_area' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'sessionpsm' => 'required|string|max:50',
            'sagile_link' => 'required|string',
            'github_link' => 'required|string',
        ]);

        return $this->studentService->updateStudent( $request->all(),$id, "PSM2");

    }

    public function PSM2ImportStudent(Request $request)
    {
        $this->authorize('import psm2 students');

        return $this->studentService->importStudents("PSM2",$request->file('file'));

    }

    public function PSM2BulkArchive(Request $request){

        $this->authorize('bulk archive psm2 students');

        return $this->studentService->bulkArchiveStudents($request->ids, "PSM2");

    }

    public function getStudentSamplePSM2(){

        // $this->studentService->getStudentSample();

        $filePath = 'import_student_sample_data_PSM2.xlsx'; // Update to CSV if needed
        
        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'Error cannot find the file');
        }
    
        return response()->download(storage_path("app/public/$filePath"));
    }

    public function PSM2StudentRequest(){

        $this->authorize('view psm2 supervisor request');

        $id = Auth::user()->id;

        $studentsDevelopment = $this->studentService->getStudentRequestSupervisor("PSM2",$id, 1);

        $studentResearch = $this->studentService->getStudentRequestSupervisor("PSM2",$id, 2);

        $userRole = Auth::user()->role === 1 ? 'Coordinator' : 'Panel';

        if ($userRole === 'Coordinator') {
            return Inertia::render('Coordinator/PSM2/StudentRequest', [
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            ]);
        } else {
            return Inertia::render('Panel/PSM2/StudentRequest', [
            'studentsDevelopment' => $studentsDevelopment,
            'studentResearch' => $studentResearch,
            ]);
        }
    }

    public function PSM2SAcceptSupervisor($id)
    {
        $this->authorize('accept psm2 supervisor');

        $studentId = $id;
        $supervisorId = Auth::user()->id;

        $this->studentService->acceptStudentsSupervisor("PSM2", $studentId, $supervisorId);
    }

    public function PSM2RejectSupervisor($id)
    {
        $this->authorize('reject psm2 supervisor');

        $studentId = $id;

        return $this->studentService->rejectStudentsSupervisor("PSM2", $studentId);
    }



}
