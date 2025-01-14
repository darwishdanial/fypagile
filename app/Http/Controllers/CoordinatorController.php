<?php

namespace App\Http\Controllers;
use App\Http\Controllers\StudentController;
use App\Models\Student;
use App\Models\ResultPSM1;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ProjectLecturerMergerService;
use App\Jobs\AssignPanelsToStudentsJob;
use Session;
use App\Jobs\EmailPanelAssignmentCompleteJob;
use Illuminate\Support\Facades\Auth;

class CoordinatorController extends Controller
{
    public function rubicPSM1(){
        return view('PSM1.coordinator.rubricPage');
    }

    public function listPSM1(){
        $students = (new StudentController)->getStudentPSM1();
      
        return view('PSM1.liststudent',compact('students'));
    }

    public function listPSM2(){
        $students = (new StudentController)->getStudentPSM2();
        // $students = Student::with('supervisor')->get();

        // dd($students);
        // $students = Student::get();
        return view('PSM2.liststudent',compact('students'));
    }

    public function totalpsm(){
        // Retrieve the data from the result_psm1 table
        $results = DB::table('result_psm1')->get();

        foreach ($results as $result) {
            $studentId = $result->studentId;
            $type = $result->type;
            $total = round($result->total, 2);

            // Check if the student record already exists in the result_totalpsm1 table
            $existingRecord = DB::table('result_totalpsm1')->where('studentId', $studentId)->first();

            if ($existingRecord) {
                // Update the existing record based on the type
                if ($type === 'supervisor') {
                    DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['sv' => $total]);
                } elseif ($type === 'panel') {
                    if ($existingRecord->panel1) {
                        DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['panel2' => $total]);
                    } else {
                        DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['panel1' => $total]);
                    }
                }
            } else {
                // Create a new record in the result_totalpsm1 table
                $data = [
                    'studentId' => $studentId,
                    'sv' => $type === 'sv' ? $total : null,
                    'panel1' => $type === 'panel' ? $total : null,
                    'panel2' => $type === 'panel' ? $total : null,
                ];

                DB::table('result_totalpsm1')->insert($data);
            }
            
        }

        // Calculate the total marks
        $students = DB::table('result_totalpsm1')->get();

        foreach ($students as $student) {
            $studentId = $student->studentId;
            $sv = $student->sv;
            $panel1 = $student->panel1;
            $panel2 = $student->panel2;

            // Calculate the total marks and update the result_totalpsm1 table
            $totalMarks = round($sv + $panel1 + $panel2, 2);
            DB::table('result_totalpsm1')->where('studentId', $studentId)->update(['totalmarks' => $totalMarks]);
        }

        return "Data inserted and total marks calculated successfully.";
    }

    public function listresultPSM1() {
        // Lists all results without any filter
        $results = DB::table('result_psm1')
            ->get();

        // List all students in results
        $students = $results->map(function($result) {
            return StudentPSM1::find($result->studentId);
        })->unique();

        // Iterate student
        // Create object containing sv, panel and total score with student id and name
        $totalResult = $students->map(function($student) use ($results){
            // error_log($student);
            $result = $results->where('studentId', $student->id);
            $supervisor = $result->where('type', 'supervisor')->map(function($svResult){ return $svResult->total; })->first();
            // $panels = $result->where('type', 'panel')->map(function($panelResult){ return $panelResult->total; });
            $panel1 = $result->where('type', 'panel1')->map(function($panelResult){ return $panelResult->total; })->first();
            $panel2 = $result->where('type', 'panel2')->map(function($panelResult){ return $panelResult->total; })->first();
            $coordinator = $result->where('type', 'coordinator')->map(function($coordinatorResult){ return $coordinatorResult->total; })->first();
            // $total = $supervisor + $panels->reduce(function($carry, $panel){ return $carry + $panel; });
            $total = $supervisor + $panel1 + $panel2 + $coordinator;
            $total = $total == 0 ? null : $total;

            return [
                'id' => $student->id,
                'name' => $student->name,
                'sv' => $supervisor,
                'panel1' => $panel1,
                'panel2' => $panel2,
                'coordinator' => $coordinator,
                'totalmarks' => $total,
            ];
        })->values();
        error_log('$totalResult');
        error_log($totalResult);
            // dd($result);
        return view('PSM1.listresult', compact('totalResult'));
        // return view('PSM1.listresult', compact('result'));
    }

    public function listresultPSM2() {
        // Lists all results without any filter
        $results = DB::table('result_psm2')
            ->get();

        // List all students in results
        $students = $results->map(function($result) {
            return StudentPSM2::find($result->studentId);
        })->unique();

        // Iterate student
        // Create object containing sv, panel and total score with student id and name
        $totalResult = $students->map(function($student) use ($results){
            // error_log($student);
            $result = $results->where('studentId', $student->id);
            $supervisor = $result->where('type', 'supervisor')->map(function($svResult){ return $svResult->total; })->first();
            // $panels = $result->where('type', 'panel')->map(function($panelResult){ return $panelResult->total; });
            $panel1 = $result->where('type', 'panel1')->map(function($panelResult){ return $panelResult->total; })->first();
            $panel2 = $result->where('type', 'panel2')->map(function($panelResult){ return $panelResult->total; })->first();
            $coordinator = $result->where('type', 'coordinator')->map(function($coordinatorResult){ return $coordinatorResult->total; })->first();
            // $total = $supervisor + $panels->reduce(function($carry, $panel){ return $carry + $panel; });
            $total = $supervisor + $panel1 + $panel2 + $coordinator;
            $total = $total == 0 ? null : $total;

            return [
                'id' => $student->id,
                'name' => $student->name,
                'sv' => $supervisor,
                'panel1' => $panel1,
                'panel2' => $panel2,
                'coordinator' => $coordinator,
                'totalmarks' => $total,
            ];
        })->values();
        error_log('$totalResult');
        error_log($totalResult);
            
        return view('PSM2.listresult', compact('totalResult'));
    }

    public function editstudentPSM1($id)
    {
        $student = StudentPSM1::where('id', $id)->first();
        if (!$student) {
            // Handle the case when the student is not found
            // For example, you could redirect the user back with an error message
            return redirect()->back()->with('error', 'Student not found.');
        }
        // echo "Student: $student";
       
        $data = ['student' => $student];
        return view('PSM1.student.edit', $data);
        // return view('welcome');
    }

    public function editstudentPSM2($id)
    {
        $student = StudentPSM2::where('id', $id)->first();
        if (!$student) {
            // Handle the case when the student is not found
            // For example, you could redirect the user back with an error message
            return redirect()->back()->with('error', 'Student not found.');
        }
        // echo "Student: $student";
       
        $data = ['student' => $student];
        return view('PSM2.student.edit', $data);
        // return view('welcome');
    }

    public function updatestudentPSM1(Request $request, StudentPSM1 $student)
    {
        $request->validate([
            'name' => 'required',
            'matric' => 'required',
        ]);

        $student->update($request->all());
        return redirect()->route('listPSM1')->with('success', 'Student has been updated successfully');
    }

    public function updatestudentPSM2(Request $request, StudentPSM2 $student)
    {
        $request->validate([
            'name' => 'required',
            'matric' => 'required',
        ]);

        $student->update($request->all());
        return redirect()->route('listPSM2')->with('success', 'Student has been updated successfully');
    }

    public function destroy(StudentPSM1 $student)
    {
        // $student = StudentPSM1::findOrFail($id); // Assuming your student model is named "student"
        $student->delete();
        return redirect()->route('listPSM1')->with('success', 'Student has been deleted successfully');

        // return view('PSM1.liststudent');
    }


    public function viewresultPSM1($id)
    {
        $student = DB::table('result_psm1')->where('studentId', $id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }


        $results = DB::table('result_psm1')
        ->where('studentId', $student->studentId)
        ->orderByRaw("FIELD(type, 'supervisor', 'panel', 'coordinator')")
        ->get();

        $data = [
            'student' => $student,
            'results' => $results,
        ];

        // dd($data);

        return view('PSM1.student.result', $data);
    }

    public function viewresultPSM2($id)
    {
        $student = DB::table('result_psm2')->where('studentId', $id)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }


        $results = DB::table('result_psm2')
        ->where('studentId', $student->studentId)
        ->orderByRaw("FIELD(type, 'supervisor', 'panel', 'coordinator')")
        ->get();

        $data = [
            'student' => $student,
            'results' => $results,
        ];

        // dd($data);

        return view('PSM2.student.result', $data);
    }

    public function cgrade(){
        $students = studentPSM1::get();
        return view('PSM1.coordinator.listpelajar',compact('students'));
    }

    public function cgrade2(){
        $students = studentPSM2::get();
        return view('PSM2.coordinator.listpelajar',compact('students'));
    }

    public function gradecoordinator($id){
        $data = ['id' => $id];
        return view('PSM1.coordinator.gradepage',$data);
    }

    public function gradecoordinator2($id){
        $data = ['id' => $id];
        return view('PSM2.coordinator.gradepage',$data);
    }

    public function markahPSM1Coordinator(Request $request){
            $data = $request->all();
                
            $ethics = ($data['similarity']/4*2)+($data['similarity']/4*3); //5%

            $studentId = $data['id'];
            $typeId = Session::get('id');
            // $panelType = (StudentPSM1::find($studentId)->first()->panelId == $typeId) ? 'panel1' : 'panel2';
            $panelType = 'coordinator';

            $existingRecord = DB::table('result_psm1')
                ->where('studentId', $studentId)
                ->where('typeId', $typeId)
                ->first();
        
            if ($existingRecord) {
                // Update the existing record
                DB::table('result_psm1')
                    ->where('studentId', $studentId)
                    ->where('typeId', $typeId)
                    ->update([
                        'ethics' =>$ethics,
                        'total' => $ethics,
                    ]);
            } else {
                // Insert a new record
                DB::table('result_psm1')->insert([
                    'studentId' => $studentId,
                    'typeId' => $typeId,
                    'type' => $panelType,
                    'ethics' =>$ethics,
                    'total' => $ethics,
                ]);
            }
        
            return redirect()->route('listcgrade')->with('success', 'Student has been graded successfully');
    }

    public function markahPSM2Coordinator(Request $request){
        $data = $request->all();
            
        $ethics = ($data['similarity']/4*2)+($data['similarity']/4*3); //5%

        $studentId = $data['id'];
        $typeId = Session::get('id');
        // $panelType = (StudentPSM1::find($studentId)->first()->panelId == $typeId) ? 'panel1' : 'panel2';
        $panelType = 'coordinator';

        $existingRecord = DB::table('result_psm2')
            ->where('studentId', $studentId)
            ->where('typeId', $typeId)
            ->first();
    
        if ($existingRecord) {
            // Update the existing record
            DB::table('result_psm2')
                ->where('studentId', $studentId)
                ->where('typeId', $typeId)
                ->update([
                    'ethics' =>$ethics,
                    'total' => $ethics,
                ]);
        } else {
            // Insert a new record
            DB::table('result_psm2')->insert([
                'studentId' => $studentId,
                'typeId' => $typeId,
                'type' => $panelType,
                'ethics' =>$ethics,
                'total' => $ethics,
            ]);
        }
    
        return redirect()->route('listcgrade')->with('success', 'Student has been graded successfully');
    }

    public function fetchPanelNames(): array{
        $response = Http::get('http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=examiner');
        
        if ($response->ok()) {
            return json_decode($response->body(), true);
        }

        throw new \Exception('Failed to fetch lecturer data');
    }
    
    public function viewPanelsPSM1(){

        $users = User::all();

        return view('PSM1.coordinator.listpanel', ['panels' => $users]);
    }

    public function viewMergeData(ProjectLecturerMergerService $mergerService){

        $mergedData = $mergerService->mergePanelAndProjectDataWithMapping();

        //dd($mergedData['typeMapping'], $mergedData['areaMapping']);

        return view('PSM1.coordinator.mldata', [
            'samples' => $mergedData['samples'],
            'labels' => $mergedData['labels'],
        ]);
    }

    public function assignPanelsToStudents()
    {
        $user = Auth::user();
        $email = $user->email;

        AssignPanelsToStudentsJob::withChain([
            new EmailPanelAssignmentCompleteJob($email),
        ])->dispatch();

        return response()->json(['message' => 'Panel assignment process has started....']);
    }



}
