<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\MLController;
use App\Http\Middleware\EnsureCoordinator;
use App\Http\Middleware\EnsurePanel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return inertia('LogIn');
})->name('login');

Route::post('/validate_login', [AuthController::class, 'validate_login'])->name('validate_login');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

Route::middleware( EnsureCoordinator::class)
    ->prefix('Coordinator')
    ->as('coordinator.')
    ->group(function () {

    Route::get('/Home', [CoordinatorController::class, 'index'])->name('home');

    Route::prefix('PSM1')
        ->as('PSM1.')
        ->group(function () {

        Route::get('/list-students', [CoordinatorController::class, 'PSM1ListStudents'])->name('listStudents');

        Route::post('/students/{id}/archive', [CoordinatorController::class, 'PSM1ArchiveStudent'])->name('students.archive');

        Route::post('/students/{id}/restore', [CoordinatorController::class, 'PSM1RestoreStudent'])->name('students.restore');

        Route::delete('/students/{id}/delete', [CoordinatorController::class, 'PSM1DeleteStudent'])->name('students.delete');

        Route::post('/students/store', [CoordinatorController::class, 'PSM1StoreStudent'])->name('students.store');

        Route::put('/students/{id}/update', [CoordinatorController::class, 'PSM1UpdateStudent'])->name('students.update');

        Route::post('/students/import', [CoordinatorController::class, 'PSM1ImportStudent'])->name('students.import');

        Route::post('/students/bulk-archive', [CoordinatorController::class, 'PSM1BulkArchiveStudent'])->name('students.bulkArchive');

        Route::get('/students/sample', function () {
            $filePath = 'import_student_sample_data.xlsx'; // Update to CSV if needed
        
            if (!Storage::disk('public')->exists($filePath)) {
                abort(404);
            }
        
            return response()->download(storage_path("app/public/$filePath"));
        })->name('students.sample');

        Route::get('/list-panels', [CoordinatorController::class, 'PSM1ListPanels'])->name('listPanels');

        Route::post('/panels/{id}/archive', [CoordinatorController::class, 'PSM1ArchivePanel'])->name('panels.archive');

        Route::post('/panels/{id}/restore', [CoordinatorController::class, 'PSM1RestorePanel'])->name('panels.restore');

        Route::post('/panels/store', [CoordinatorController::class, 'PSM1StorePanel'])->name('panels.store');

        Route::put('/panels/{id}/update', [CoordinatorController::class, 'PSM1UpdatePanel'])->name('panels.update');

        Route::delete('/panels/{id}/delete', [CoordinatorController::class, 'PSM1DeletePanel'])->name('panels.delete');

        Route::post('/panels/bulk-archive', [CoordinatorController::class, 'PSM1BulkArchivePanel'])->name('panels.bulkArchive');

        Route::post('/panels/import', [CoordinatorController::class, 'ImportPanels'])->name('panels.import');

        Route::get('/panels/sample', function () {
            $filePath = 'import_panels_sample_data.xlsx'; // Update to CSV if needed
        
            if (!Storage::disk('public')->exists($filePath)) {
                abort(404);
            }
        
            return response()->download(storage_path("app/public/$filePath"));
        })->name('panels.sample');

        //supervisor

        Route::get('/list-supervisor', [CoordinatorController::class, 'PSM1ListSupervisor'])->name('listSupervisor');

        Route::get('/list-student-supervisor/{id}', [CoordinatorController::class, 'PSM1SupervisorStudentList'])->name('supervisor.studentList');

        Route::post('/assign-supervisor', [CoordinatorController::class, 'PSM1SAssignSupervisor'])->name('supervisor.assign');

        Route::post('/unassign-supervisor/{id}', [CoordinatorController::class, 'PSM1UnassignSupervisor'])->name('supervisor.unassign');



        //panel proposal

        Route::get('/list-proposal-panel', [CoordinatorController::class, 'PSM1ListProposalPanel'])->name('listProposalPanel');

        Route::get('/list-student-panel-proposal', [CoordinatorController::class, 'PSM1ProposalPanelStudentList'])->name('panelProposal.studentList');

        Route::post('/assign-proposal-panel-1', [CoordinatorController::class, 'PSM1SAssignProposalPanel1'])->name('panelProposal1.assign');

        Route::post('/assign-proposal-panel-2', [CoordinatorController::class, 'PSM1SAssignProposalPanel2'])->name('panelProposal2.assign');

        Route::post('/unassign-proposal-panel-1/{id}', [CoordinatorController::class, 'PSM1UnassignProposalPanel1'])->name('panelProposal1.unassign');

        Route::post('/unassign-proposal-panel-2/{id}', [CoordinatorController::class, 'PSM1UnassignProposalPanel2'])->name('panelProposal2.unassign');


        //panel PSM1
        Route::get('/list-PSM1-panel', [CoordinatorController::class, 'PSM1listAssignPanel'])->name('listPSM1Panel');

        Route::get('/list-student-panel-PSM1', [CoordinatorController::class, 'PSM1PanelStudentList'])->name('panel.studentList');

        Route::post('/assign-PSM1-panel-1', [CoordinatorController::class, 'PSM1SAssignPanel1'])->name('panel1.assign');

        Route::post('/assign-PSM1-panel-2', [CoordinatorController::class, 'PSM1SAssignPanel2'])->name('panel2.assign');

        Route::post('/unassign-PSM1-panel-1/{id}', [CoordinatorController::class, 'PSM1UnassignPSMPanel1'])->name('panel1.unassign');

        Route::post('/unassign-PSM1-panel-2/{id}', [CoordinatorController::class, 'PSM1UnassignPSMPanel2'])->name('panel2.unassign');

        Route::post('/auto-assign-PSM1-panel', [CoordinatorController::class, 'PSM1autoAssignPanelsToStudents'])->name('panel.autoAssign');



        Route::get('/view-result', [CoordinatorController::class, 'PSM1ViewResult'])->name('viewResult');

        Route::get('/evaluation-rubric', [CoordinatorController::class, 'PSM1EvaluationRurbric'])->name('evaluationRubric');

        Route::get('/grade-supervision', [CoordinatorController::class, 'PSM1GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-proposal', [CoordinatorController::class, 'PSM1GradeProposal'])->name('gradeProposal');

        Route::get('/grade-PSM1', [CoordinatorController::class, 'PSM1Grade'])->name('gradePSM1');

    });

    Route::prefix('PSM2')
        ->as('PSM2.')
        ->group(function () {

        Route::get('/list-students', [CoordinatorController::class, 'PSM2ListStudents'])->name('listStudents');

        Route::post('/students/{id}/archive', [CoordinatorController::class, 'PSM2ArchiveStudent'])->name('students.archive');

        Route::post('/students/{id}/restore', [CoordinatorController::class, 'PSM2RestoreStudent'])->name('students.restore');

        Route::delete('/students/{id}/delete', [CoordinatorController::class, 'PSM2DeleteStudent'])->name('students.delete');

        Route::post('/students/store', [CoordinatorController::class, 'PSM2StoreStudent'])->name('students.store');

        Route::put('/students/{id}/update', [CoordinatorController::class, 'PSM2UpdateStudent'])->name('students.update');

        Route::post('/students/import', [CoordinatorController::class, 'PSM2ImportStudent'])->name('students.import');

        Route::post('/students/bulk-archive', [CoordinatorController::class, 'PSM2BulkArchive'])->name('students.bulkArchive');

        Route::get('/list-panels', [CoordinatorController::class, 'PSM2ListPanels'])->name('listPanels');

        Route::post('/panels/{id}/archive', [CoordinatorController::class, 'PSM2ArchivePanel'])->name('panels.archive');

        Route::post('/panels/{id}/restore', [CoordinatorController::class, 'PSM2RestorePanel'])->name('panels.restore');

        Route::delete('/panels/{id}/delete', [CoordinatorController::class, 'PSM2DeletePanel'])->name('panels.delete');

        Route::post('/panels/bulk-archive', [CoordinatorController::class, 'PSM2BulkArchivePanel'])->name('panels.bulkArchive');

        Route::post('/panels/store', [CoordinatorController::class, 'PSM2StorePanel'])->name('panels.store');

        Route::put('/panels/{id}/update', [CoordinatorController::class, 'PSM2UpdatePanel'])->name('panels.update');

        //supervisor
        Route::get('/list-supervisor', [CoordinatorController::class, 'PSM2ListSupervisor'])->name('listSupervisor');

        Route::get('/list-student-supervisor/{id}', [CoordinatorController::class, 'PSM2SupervisorStudentList'])->name('supervisor.studentList');

        Route::post('/assign-supervisor', [CoordinatorController::class, 'PSM2SAssignSupervisor'])->name('supervisor.assign');

        Route::post('/unassign-supervisor/{id}', [CoordinatorController::class, 'PSM2UnassignSupervisor'])->name('supervisor.unassign');



        //panel PSM2
        Route::get('/list-PSM2-panel', [CoordinatorController::class, 'PSM2ListPanel'])->name('listPSM2Panel');
        
        Route::get('/list-student-panel-PSM2', [CoordinatorController::class, 'PSM2PanelStudentList'])->name('panel.studentList');

        Route::post('/assign-PSM2-panel-1', [CoordinatorController::class, 'PSM2SAssignPanel1'])->name('panel1.assign');

        Route::post('/assign-PSM2-panel-2', [CoordinatorController::class, 'PSM2SAssignPanel2'])->name('panel2.assign');

        Route::post('/unassign-PSM2-panel-1/{id}', [CoordinatorController::class, 'PSM2UnassignPSMPanel1'])->name('panel1.unassign');

        Route::post('/unassign-PSM2-panel-2/{id}', [CoordinatorController::class, 'PSM2UnassignPSMPanel2'])->name('panel2.unassign');


        Route::get('/view-result', [CoordinatorController::class, 'PSM2ViewResult'])->name('viewResult');

        Route::get('/evaluation-rubric', [CoordinatorController::class, 'PSM2EvaluationRurbric'])->name('evaluationRubric');

        Route::get('/grade-supervision', [CoordinatorController::class, 'PSM2GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-PSM2', [CoordinatorController::class, 'PSM2Grade'])->name('gradePSM2');

    });

});


Route::middleware(EnsurePanel::class)
    ->prefix('Panel')
    ->as('panel.')
    ->group(function () {

    Route::get('/Home', [PanelController::class, 'index'])->name('home');

    Route::prefix('PSM1')
        ->as('PSM1.')
        ->group(function () {

        Route::get('/grade-supervision', [PanelController::class, 'PSM1GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-proposal', [PanelController::class, 'PSM1GradeProposal'])->name('gradeProposal');

        Route::get('/grade-PSM1', [PanelController::class, 'PSM1Grade'])->name('gradePSM1');

    });

    Route::prefix('PSM2')
        ->as('PSM2.')
        ->group(function () {

        Route::get('/grade-supervision', [PanelController::class, 'PSM2GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-PSM2', [PanelController::class, 'PSM2Grade'])->name('gradePSM2');
    });
});

// Route::get('/Coordinator/Home', function () {
//     return inertia('/Coordinator/Home/Index');
// });

// Route::get('/Coordinator/PSM1/list-students', function () {
//     return inertia('/Coordinator/PSM1/ListStudents');
// });

// Route::get('/Coordinator/PSM1/list-panels', function () {
//     return inertia('/Coordinator/PSM1/ListPanels');
// });


Route::get('/Coordinator/PSM1', function () {
    return inertia('PSM1');
});

Route::get('/Coordinator/PSM2', function () {
    return inertia('PSM2');
});

// Route::get('login', function () {
//     return redirect()->route('index');
// });


Route::controller(AuthController::class)->group(function(){

    //Route::get('/', 'index')->name('index');

    //Route::get('login', 'index')->redirect()->route('index');

    Route::get('registration', 'registration')->name('registration');

    // Route::get('logout', 'logout')->name('logout');

    Route::post('validate_registration', 'validate_registration')->name('auth.validate_registration');

    Route::post('validate_login', 'validate_login')->name('auth.validate_login');

    Route::get('change-password', 'changePassword')->name('change-password');
    Route::post('change-password',  'updatePassword')->name('update-password');

    

});

Route::controller(UserController::class)->group(function(){
    //route, function, callfunction
    Route::get('users', 'index')->name('importusers');
    Route::get('users-export', 'export')->name('users.export');
    Route::post('users-import', 'import')->name('users.import');

    Route::get('listusers', 'listusers')->name('listusers');

    Route::get('users/edit/{user}', 'edit')->name('users.edit');
    Route::put('users/update/{user}', 'update')->name('users.update');
    Route::delete('users/delete/{user}', 'destroy')->name('users.delete');

    // Route::resource('users', UserController::class);

    
});

Route::controller(StudentController::class)->group(function(){
    Route::get('students', 'index')->name('importstudents');
    Route::get('students-export', 'export')->name('students.export');
    Route::post('students-import', 'import')->name('students.import');

    Route::get('assign-students', 'getSupervisors')->name('get.supervisors');
    Route::get('getStudents', 'getStudents')->name('get.students');
    Route::get('assignStudent', 'assignStudent')->name('assign.student');
    Route::get('unassignStudent', 'unassignStudent')->name('unassign.student');

    //import psm1 students
    Route::get('studentPSM1', 'studentPSM1')->name('importpsm1');
    Route::post('file-import', 'importPSM1')->name('studentspsm1.import');
    
    //import psm2 students
    Route::get('studentPSM2', 'studentPSM2')->name('importpsm2');;
    Route::post('file-import2', 'importPSM2')->name('studentspsm2.import');

});

Route::controller(CoordinatorController::class)->group(function(){
    // Route::get('coordinator', 'indexC')->name('indexC');

    Route::post('/assign-panels-to-students', 'autoAssignPanelsToStudentsPSM1')->name('autoAssign');
    Route::get('/assign-panels-to-students', 'deleteAllAssignedPanels')->name('removeAllPanel');

    Route::get('/project-area', 'getMLData')->name('getMLData');

    Route::get('coordinator-rubric/PSM1', 'rubicPSM1')->name('rubricPSM1');

    Route::get('coordinator-students/PSM1', 'listPSM1')->name('listPSM1');
    Route::get('coordinator-students/PSM2', 'listPSM2')->name('listPSM2');

    Route::get('coordinator-panels/PSM1', 'viewPanelsPSM1')->name('viewPanelsPSM1');
    Route::get('coordinator-mergedata/PSM1', 'viewMergeData')->name('viewMergeData');

    Route::get('coordinator/students/PSM1/{id}', 'editstudentPSM1')->name('editstudentPSM1'); //edit student profile
    Route::put('coordinator/students/PSM1/update/{id}', 'updatestudentPSM1')->name('updatestudentPSM1'); //update student profile
    Route::get('coordinator/students/PSM1/delete/{student}', 'destroy')->name('studentdestroy'); //delete using get


    Route::get('coordinator/PSM1/result', 'listresultPSM1')->name('listresultPSM1');
    Route::get('coordinator/PSM2/result', 'listresultPSM2')->name('listresultPSM2');

    Route::get('coordinator/PSM1/result/{id}', 'viewresultPSM1')->name('viewresultPSM1'); //view psm1
    Route::get('coordinator/PSM2/result/{id}', 'viewresultPSM2')->name('viewresultPSM2'); //view psm2

    //grade psm1
    Route::get('coordinator/PSM1/grade', 'cgrade')->name('listcgrade');
    Route::get('coordinator/PSM1/grade/{id}', 'gradecoordinator')->name('gradecoordinator'); //display grade page
    Route::post('coordinator/PSM1/grade', 'markahPSM1Coordinator')->name('sendgrade'); //store grade

    //grade psm2
    Route::get('coordinator/PSM2/grade', 'cgrade2')->name('listcgrade2');
    Route::get('coordinator/PSM2/grade/{id}', 'gradecoordinator2')->name('gradecoordinator2'); //display grade page
    Route::post('coordinator/PSM2/grade', 'markahPSM2Coordinator')->name('sendgrade2'); //store grade

});

Route::controller(SupervisorController::class)->group(function(){

    //PSM1
    Route::get('PSM1/supervisor-students', 'listsvstudent')->name('listsvstudent');
    Route::get('supervisor/creategrade/{student}', 'creategradePSM1')->name('creategradePSM1'); //display grade page
    Route::post('supervisor/grade/{student}', 'gradePSM1')->name('gradePSM1'); //store grade
    // Route::get('supervisor/viewgrade/{student}', 'viewgradePSM1')->name('viewPSM1'); //view grade
    
    //PSM2
    Route::get('PSM2/supervisor-students', 'listsvstudent2')->name('listsvstudent2');
    Route::get('supervisor/creategrade2/{student}', 'creategradePSM2')->name('svgradePSM2'); //display grade page
    Route::post('supervisor/grade2/{student}', 'gradePSM2')->name('gradePSM2'); //store grade

    // Rubric Agile
    Route::get('supervisor-pelajar/PSM1', 'listsvpelajar')->name('listsvpelajar');
    Route::get('supervisor/gradebaru/PSM1/{id}', 'gradebaruPSM1')->name('gradebaruPSM1'); //display grade page
    Route::post('supervisor/markah/PSM1', 'markahPSM1')->name('markahPSM1'); //store grade

    Route::get('supervisor-pelajar/PSM2', 'listsvpelajar2')->name('listsvpelajar2');
    Route::get('supervisor/gradebaru/PSM2/{id}', 'gradebaruPSM2')->name('gradebaruPSM2'); //display grade page
    Route::post('supervisor/markah/PSM2', 'markahPSM2')->name('markahPSM2'); //store grade
    

});

Route::controller(PanelController::class)->group(function(){
    //PSM1
    Route::get('PSM1/panel/listpanel', 'getPanel')->name('get.panel');
    Route::get('PSM1/panel/liststudents', 'getStudents')->name('get.panelstudents');
    Route::get('PSM1/panel/assignStudent', 'assignStudent')->name('assign.panelstudent');
    Route::get('PSM1/panel/unassignStudent', 'unassignStudent')->name('unassign.panelstudent');

    //gradePSM1
    Route::get('panel-pelajar/PSM1', 'listpanelpelajar')->name('listpanelpelajar');
    Route::get('panel/gradePSM1/{id}', 'gradePSM1Panel')->name('gradePSM1Panel'); //display grade page
    Route::post('panel/markah/PSM1', 'markahPSM1Panel')->name('markahPSM1Panel'); //store grade

    //gradePSM1
    Route::get('panel-pelajar/PSM2', 'listpanelpelajar2')->name('listpanelpelajar2');
    Route::get('panel/gradePSM2/{id}', 'gradePSM2Panel')->name('gradePSM2Panel'); //display grade page
    Route::post('panel/markah/PSM2', 'markahPSM2Panel')->name('markahPSM2Panel'); //store grade



    //PSM2
    Route::get('PSM2/panel/listpanel', 'getPanelPSM2')->name('psm2.get.panel');
    Route::get('PSM2/panel/liststudents', 'getStudentsPSM2')->name('psm2.get.panelstudents');
    Route::get('PSM2/panel/assignStudent', 'assignStudentPSM2')->name('psm2.assign.panelstudent');
    Route::get('PSM2/panel/unassignStudent', 'unassignStudentPSM2')->name('psm2.unassign.panelstudent');

    //proposal
    Route::get('proposal/panel/listpanel', 'getPanelProposal')->name('proposal.get.panel');
    Route::get('proposal/panel/liststudents', 'getStudentsProposal')->name('proposal.get.panelstudents');
    Route::get('proposal/panel/assignStudent', 'assignStudentProposal')->name('proposal.assign.panelstudent');
    Route::get('proposal/panel/unassignStudent', 'unassignStudentProposal')->name('proposal.unassign.panelstudent');

    //gradeproposal
    Route::get('proposal/panel/list', 'getStudentPanelProposal')->name('studentproposal');
    Route::get('proposal/panel/list/{id}', 'gradeProposal')->name('gradeproposal');
    Route::post('proposal/panel/grade', 'markahProposal')->name('markahproposal'); //store grade

    
});