<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentPSM1;
use App\Models\StudentPSM2;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Jobs\AssignPanelsToStudentsJob;
use App\Jobs\EmailPanelAssignmentCompleteJob;
use Illuminate\Support\Facades\Auth;
use Session;

class CoordinatorService
{
    public function getStudentPSM1()
    {
        return StudentPSM1::all();
    }

    public function getStudentPSM2()
    {
        return StudentPSM2::all();
    }

    public function getTotalMarksPSM1()
    {
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

    public function getResultPSM1()
    {
        // Fetch and calculate the results for PSM1 students
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

        return $totalResult;
        // error_log('$totalResult');
        // error_log($totalResult);
            // dd($result);
    }

    public function getResultPSM2(){
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

        return $totalResult;
        // error_log('$totalResult');
        // error_log($totalResult);
    }

    public function editstudentPSM1($id){

        $student = StudentPSM1::where('id', $id)->first();
        if (!$student) {
            // Handle the case when the student is not found
            // For example, you could redirect the user back with an error message
            return redirect()->back()->with('error', 'Student not found.');
        }
        // echo "Student: $student";
       
        $data = ['student' => $student];

        return $data;
    }

    public function editstudentPSM2($id){

        $student = StudentPSM2::where('id', $id)->first();
        if (!$student) {
            // Handle the case when the student is not found
            // For example, you could redirect the user back with an error message
            return redirect()->back()->with('error', 'Student not found.');
        }
        // echo "Student: $student";
       
        $data = ['student' => $student];

        return $data;
    }

    public function viewResultPSM1($id){

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

        return $data;
    }

    public function viewResultPSM2($id){
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

        return $data;
    }

    public function getMarkahPSM1Coordinator($data){
                
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
        
    }

    public function getMarkahPSM2Coordinator($data){

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
    
    }

    public function fetchPanelNames(): array
    {
        $response = Http::get('http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=examiner');
        
        if ($response->ok()) {
            return json_decode($response->body(), true);
        }

        throw new \Exception('Failed to fetch lecturer data');
    }

    public function autoAssignPanelsToStudents($psmType, $email)
    {
        $status = "success";

        // AssignPanelsToStudentsJob::withChain([
        //     new EmailPanelAssignmentCompleteJob($email, $status),
        // ])->dispatch($psmType, $email);

        AssignPanelsToStudentsJob::dispatch($psmType, $email);

        //return response()->json(['message' => 'AI Panel assignment process has started....']);
    }

    public function deleteAllAssignedPanels(){

        StudentPSM1::query()->update([
            'panelId' => null,
            'panel2Id' => null,
        ]);
    
        return response()->json(['message' => 'All panel assignments have been cleared successfully.']);
    }


}
