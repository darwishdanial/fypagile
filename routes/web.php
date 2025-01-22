<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\PanelController;
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

Route::get('login', function () {
    return redirect()->route('index');
});

Route::controller(AuthController::class)->group(function(){

    Route::get('/', 'index')->name('index');

    // Route::get('login', 'index')->redirect()->route('index');

    Route::get('registration', 'registration')->name('registration');

    Route::get('logout', 'logout')->name('logout');

    Route::post('validate_registration', 'validate_registration')->name('auth.validate_registration');

    Route::post('validate_login', 'validate_login')->name('auth.validate_login');

    Route::get('change-password', 'changePassword')->name('change-password');
    Route::post('change-password',  'updatePassword')->name('update-password');

    Route::get('dashboard', 'dashboard')->name('dashboard');

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