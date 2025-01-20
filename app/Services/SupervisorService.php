<?php

namespace App\Services;

use App\Models\Supervisor;
use Illuminate\Support\Facades\DB;
use Session;


class SupervisorService {

    public function totalSupervisor(){
        
        return Supervisor::count();;
    }

    public function gradePSM1($data, $student){
        
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

    }

    public function gradePSM2($data, $student){

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


    }

    public function markahPSM1($data){

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

    }

    public function markahPSM2($data){

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
    }
}