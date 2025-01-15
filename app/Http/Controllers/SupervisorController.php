<?php

namespace App\Http\Controllers;
use App\Models\Supervisor;
use App\Models\Student;
use App\Models\ResultPSM1;
use App\Models\ResultPSM2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Services\StudentService;
use App\Services\SupervisorService;


class SupervisorController extends Controller
{
    protected $studentService;
    protected $supervisorService;

    public function __construct(StudentService $studentService, SupervisorService $supervisorService){

        $this->studentService = $studentService;
        $this->supervisorService = $supervisorService;
    }
    public function totalSupervisor(){

        return $this->supervisorService->totalSupervisor();
    }

    public function listsvstudent(){

        $students = $this->studentService->getStudentSupervisor();

        return view('PSM1.liststudentsv',compact('students'));
    }

    //display gradepage for supervisor
    public function creategradePSM1(Student $student){

        return view('PSM1.svgradeform', compact('student'));
    }

    public function gradePSM1(Request $request, Student $student){
        
        $request->validate([
            'logbook' => 'required',
            'meeting' => 'required',
            'ethic' => 'required',
            'independent' => 'required',
            'chapter1' => 'required',
            'chapter2' => 'required',
            'format' =>'required',
            'citation' => 'required',
            'chapter3' => 'required',
            'chapter4' => 'required',
            'format2' => 'required',
            'citation2' => 'required',
            'abstract' => 'required',
            'complete1' => 'required',
            'complete2' => 'required',
            'complete3' => 'required',
            'complete4' => 'required',
            'format3' => 'required',
            'citation3' => 'required',
        ]);

        $data = $request->all();
        
        DB::table('result_psm1')->insert([
            'studentId' => $student->id,
            'svId' => Session::get('id'),
            'logbook' => $data['logbook'],
            'meeting' => $data['meeting'],
            'ethic' => $data['ethic'],
            'independent' => $data['independent'],
            'chapter1' => $data['chapter1'],
            'chapter2' => $data['chapter2'],
            'format' => $data['format'],
            'citation' => $data['citation'],
            'chapter3' => $data['chapter3'],
            'chapter4' => $data['chapter4'],
            'format2' => $data['format2'],
            'citation2' => $data['citation2'],
            'abstract' => $data['abstract'],
            'complete1' => $data['complete1'],
            'complete2' => $data['complete2'],
            'complete3' => $data['complete3'],
            'complete4' => $data['complete4'],
            'format3' => $data['format3'],
            'citation3' => $data['citation3'],
        ]);

        return redirect()->route('listsvstudent')->with('success','Student Has Been graded successfully');
        // return view('PSM1.svgradeform', compact('students'));
    }

    public function listsvstudent2(){

        $students = $this->studentService->getStudentSupervisor2();
        
        return view('PSM2.liststudentsv',compact('students'));
    }

    //display gradepage for supervisor
    public function creategradePSM2(Student $student){
        return view('PSM2.svgradeform', compact('student'));
    }

    public function gradePSM2(Request $request, Student $student){
        
        $request->validate([
            'logbook' => 'required',
            'meeting' => 'required',
            'ethic' => 'required',
            'independent' => 'required',
        ]);

        $data = $request->all();
        
        DB::table('result_psm2')->insert([
            // 'name' => $request->input('name'),
            'studentId' => $student->id,
            'svId' => Session::get('id'),
            'logbook' => $data['logbook'],
            'meeting' => $data['meeting'],
            'ethic' => $data['ethic'],
            'independent' => $data['independent'],
            'report' => $data['report'],
            'chapter5' => $data['chapter5'],
            'format' => $data['format'],
            'citation' => $data['citation'],
            'milestone' => $data['milestone'],
            'execution' => $data['execution'],
            'knowledge' => $data['knowledge'],
            'citation2' => $data['citation2'],
            'milestone2' => $data['milestone2'],
            'execution2' => $data['execution2'],
            'knowledge2' => $data['knowledge2'],
            'originality' => $data['originality'],
            'technical' => $data['technical'],
            'clarity' => $data['clarity'],
            //'citation2' => $data['citation2'],
            'asbtract' => $data['asbtract'],
            'intro' => $data['intro'],
            'literature' => $data['literature'],
            'methodology' => $data['methodology'],
            'analysis' => $data['analysis'],
            'design' => $data['design'],
            'implementation' => $data['implementation'],
            'conclusion' => $data['conclusion'],
            'format2' => $data['format2'],
            'citation3' => $data['citation3'],
            'objective' => $data['objective'],
            'coding' => $data['coding'],
            'completeness' => $data['completeness'],
            'service' => $data['service'],
            'elements' => $data['elements'],
            'creativity' => $data['creativity'],
        ]);

        return redirect()->route('listsvstudent2')->with('success','Student Has Been graded successfully');
        // return view('PSM1.svgradeform', compact('students'));
    }

    //rubric agile PSM1
    public function listsvpelajar(){

        $students = $this->studentService->getStudentSupervisor();
        
        return view('PSM1.supervisor.listpelajarsv',compact('students'));
    }

    public function gradebaruPSM1($id){
        $data = ['id' => $id];
        return view('PSM1.svgradepage',$data);
    }

        public function markahPSM1(Request $request)
        {
            $request->validate([
                'logbook' => 'required',
                'meetingf' => 'required',
                'work' => 'required',
                'selfreliance' => 'required',
                'iteration1' => 'required',
                'iteration2' => 'required',
                'writing' => 'required',
                'iteration3' => 'required',
                'iteration4' => 'required',
                'writing2' => 'required',
                'citation2' => 'required',
                'abstract' => 'required',
                'completei1' => 'required',
                'completei2' => 'required',
                'completei3' => 'required',
                'completei4' => 'required',
                'writing3' => 'required',
                'citation3' => 'required',
            ]);

            $data = $request->all();
            $supervision = ($data['logbook'] / 4 * 2) + ($data['meetingf'] / 4 * 3) + ($data['work'] / 4 * 3) + ($data['selfreliance'] / 4 * 2);
            $progressreport1 = ($data['iteration1'] / 4 * 3) + ($data['iteration2'] / 4 * 4) + ($data['writing'] / 4 * 2) + ($data['citation'] / 4 * 1);
            $progressreport2 = ($data['iteration3'] / 4 * 3) + ($data['iteration4'] / 4 * 4) + ($data['writing2'] / 4 * 2) + ($data['citation2'] / 4 * 1);
            $finalreport = ($data['abstract'] / 4 * 2) + ($data['completei1'] / 4 * 5) + ($data['completei2'] / 4 * 6) + ($data['completei3'] / 4 * 6) + ($data['completei4'] / 4 * 6) + ($data['writing3'] / 4 * 3) + $data['citation3'] / 4 * 2;
            $design = ($data['architecture'] / 4 * 3) + ($data['requirement'] / 4 * 3) + ($data['database'] / 4 * 4) + ($data['uml'] / 4 * 2) + ($data['gantt'] / 4 * 1) + ($data['interface'] / 4 * 2) + ($data['element'] / 4 * 5) + ($data['testing'] / 4 * 2) + ($data['coding1'] / 4 * 3);
            $totalshared = ($finalreport + $design) / 3;
            $total = $supervision + $progressreport1 + $progressreport2 + $totalshared;
        
            $typeId = Session::get('id');
            $studentId = $data['id'];
        
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
                        'supervision' => $supervision,
                        'progressreport1' => $progressreport1,
                        'progressreport2' => $progressreport2,
                        'finalreport' => $finalreport,
                        'design' => $design,
                        'totalshared' => $totalshared,
                        'total' => $total,
                    ]);
            } else {
                // Insert a new record
                DB::table('result_psm1')->insert([
                    'studentId' => $studentId,
                    'typeId' => $typeId,
                    'type' => 'supervisor',
                    'supervision' => $supervision,
                    'progressreport1' => $progressreport1,
                    'progressreport2' => $progressreport2,
                    'finalreport' => $finalreport,
                    'design' => $design,
                    'totalshared' => $totalshared,
                    'total' => $total,
                ]);
            }
        
            return redirect()->route('listsvpelajar')->with('success', 'Student has been graded successfully');
        }
        

    //rubric agile PSM2
    public function listsvpelajar2(){
        $students = $this->studentService->getStudentSupervisor2();
        
        return view('PSM2.supervisor.listpelajarsv',compact('students'));
    }

    public function gradebaruPSM2($id){
        $data = ['id' => $id];
        return view('PSM2.svgradeform',$data);

        // $entry = ResultPSM1::findOrFail($id);
        // $data = ['entry' => $entry, 'id' => $id];
        // return view('PSM1.svgradepage', $data);
    }

    public function markahPSM2(Request $request){
        
        // $request->validate([
        //     'proposal' => 'required',
        //     'planning' => 'required',
        //     'clo1' => 'required',
        //     'clo2' => 'required',
        //     'i2clo2' => 'required',
        //     'i2clo1' => 'required',
        //     'i3clo1' => 'required',
        //     'i3clo2' => 'required',
        //     'i4clo1' => 'required',
        //     'i4clo2' => 'required',
        //     'report' => 'required',
        //     'presentation' => 'required',
        //     'application' => 'required',
        // ]);

        $data = $request->all();
        
        $shortpaper = ($data['originality']/4*1)+($data['technical']/4*2)+($data['clarity']/4*1)+($data['format']/4*1); /*5*/
        $finalreport = ($data['abstract']/4*1)+($data['introduction']/4*1)+($data['literature']/4*2)+($data['methodology']/4*2)+($data['revised']/4*4)+($data['implementation']/4*6)+($data['revisedt']/4*5)+($data['conclusion']/4*2)+($data['writing2']/4*1)+($data['citation']/4*1); //25
        $system = ($data['scope']/4*3)+($data['coding']/4*5)+($data['completeness']/4*6)+($data['service']/4*2)+($data['elements']/4*10)+($data['interface']/4*4); //30
        
        $totalshared = ($shortpaper+$finalreport+$system)/3;

        $supervision = ($data['logbook']/4*2)+($data['meeting']/4*3)+($data['ethic']/4*3)+($data['independent']/4*2); //10
        $progressreport = ($data['improvement']/4*1)+($data['revisedc4']/4*1)+($data['progressc5']/4*1)+($data['writing']/4*1)+($data['citation']/4*1); //5
        $projectprogress = ($data['iteration3']/4*2)+($data['execution']/4*2)+($data['knowledge']/4*1); //5
        $projectprogress2 = ($data['iteration6']/4*2)+($data['execution2']/4*2)+($data['knowledge2']/4*1); //5
        
        $total = $supervision+$progressreport+$projectprogress+$projectprogress2+$totalshared;
        
        $typeId = Session::get('id');
        $studentId = $data['id'];
    
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
                        'shortpaper' => $shortpaper,
                        'finalreport' => $finalreport,
                        'system' => $system,
                        'supervision' => $supervision,
                        'progressreport' => $progressreport,
                        'projectprogress' => $projectprogress,
                        'projectprogress2' => $projectprogress2,
                        'totalshared' => $totalshared,
                        'total' => $total,
                    ]);
            } else {
                // Insert a new record
                DB::table('result_psm2')->insert([
                    'studentId' => $data['id'],
                    'typeId' => Session::get('id'),
                    'type' => 'supervisor',
                    'shortpaper' => $shortpaper,
                    'finalreport' => $finalreport,
                    'system' => $system,
                    'supervision' => $supervision,
                    'progressreport' => $progressreport,
                    'projectprogress' => $projectprogress,
                    'projectprogress2' => $projectprogress2,
                    'totalshared' => $totalshared,
                    'total' => $total,
                    
                ]);
            }
        
    

       

        return redirect()->route('listsvpelajar2')->with('success','Student Has Been graded successfully');
        // return view('PSM1.listpelajarsv', compact('students'));
    }
    
}
