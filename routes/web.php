<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\RubricCriteriaController;
use App\Http\Controllers\MLController;
use App\Http\Middleware\EnsureCoordinator;
use App\Http\Middleware\EnsurePanel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

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

    // Route::get('/Home', [CoordinatorController::class, 'index'])->name('home');

    Route::get('/Home', [CoordinatorController::class, 'Dashboard'])->name('dashboard');

    Route::get('/Home/panel-history', [CoordinatorController::class, 'getPanelHistory'])->name('panel.history');

    Route::get('/Home/ml-data', [CoordinatorController::class, 'getMLData'])->name('panel.ml-data');

    Route::get('/export-ai-data', [CoordinatorController::class, 'exportAiData'])->name('export.ai-data');

    Route::get('/matched-categories', [CoordinatorController::class, 'getSampleData'])->name('matched-categories.ai-data');

    Route::get('/test-panel-api', [CoordinatorController::class, 'PSM1PredictPanel'])->name('api.test');


    Route::prefix('PSM1')
        ->as('PSM1.')
        ->group(function () {

        //STUDENT MANAGEMENT

        Route::get('/list-students', [StudentController::class, 'PSM1ListStudents'])->name('listStudents');

        Route::post('/students/{id}/archive', [StudentController::class, 'PSM1ArchiveStudent'])->name('students.archive');

        Route::post('/students/{id}/restore', [StudentController::class, 'PSM1RestoreStudent'])->name('students.restore');

        Route::delete('/students/{id}/delete', [StudentController::class, 'PSM1DeleteStudent'])->name('students.delete');

        Route::post('/students/store', [StudentController::class, 'PSM1StoreStudent'])->name('students.store');

        Route::put('/students/{id}/update', [StudentController::class, 'PSM1UpdateStudent'])->name('students.update');

        Route::post('/students/import', [StudentController::class, 'PSM1ImportStudent'])->name('students.import');

        Route::post('/students/bulk-archive', [StudentController::class, 'PSM1BulkArchiveStudent'])->name('students.bulkArchive');

        Route::get('/students/sample',[StudentController::class, 'getStudentSamplePSM1'] )->name('students.sample');

        //PANEL MANAGEMENT

        Route::get('/list-panels', [PanelController::class, 'PSM1ListPanels'])->name('listPanels');

        Route::post('/panels/{id}/archive', [PanelController::class, 'PSM1ArchivePanel'])->name('panels.archive');

        Route::post('/panels/{id}/restore', [PanelController::class, 'PSM1RestorePanel'])->name('panels.restore');

        Route::post('/panels/store', [PanelController::class, 'PSM1StorePanel'])->name('panels.store');

        Route::put('/panels/{id}/update', [PanelController::class, 'PSM1UpdatePanel'])->name('panels.update');

        Route::delete('/panels/{id}/delete', [PanelController::class, 'PSM1DeletePanel'])->name('panels.delete');

        Route::post('/panels/bulk-archive', [PanelController::class, 'PSM1BulkArchivePanel'])->name('panels.bulkArchive');

        Route::post('/panels/import', [PanelController::class, 'ImportPanels'])->name('panels.import');

        Route::get('/panels/sample', [PanelController::class, 'getPanelSample'])->name('panels.sample');

        //SUPERVISOR ASSIGNMENT

        Route::get('/list-supervisor', [CoordinatorController::class, 'PSM1ListSupervisor'])->name('listSupervisor');

        Route::get('/list-student-supervisor/{id}', [CoordinatorController::class, 'PSM1SupervisorStudentList'])->name('supervisor.studentList');

        Route::post('/request-supervisor', [CoordinatorController::class, 'PSM1RequestSupervisor'])->name('supervisor.request');
        
        Route::post('/cancel-request-supervisor/{id}', [CoordinatorController::class, 'PSM1CancelRequestSupervisor'])->name('supervisor.cancelRequest');

        Route::post('/assign-supervisor', [CoordinatorController::class, 'PSM1SAssignSupervisor'])->name('supervisor.assign');

        Route::post('/unassign-supervisor/{id}', [CoordinatorController::class, 'PSM1UnassignSupervisor'])->name('supervisor.unassign');

        Route::get('/sv-req', [StudentController::class, 'PSM1StudentRequest'])->name('sv.req');

        Route::post('/sv-accept/{id}', [StudentController::class, 'PSM1SAcceptSupervisor'])->name('sv.accept');

        Route::post('/sv-reject/{id}', [StudentController::class, 'PSM1RejectSupervisor'])->name('sv.reject');

        //PANEL ASSIGNMENT

        Route::get('/list-panel', [CoordinatorController::class, 'PSM1listAssignPanel'])->name('listPSM1Panel');

        Route::get('/list-student-panel-PSM1', [CoordinatorController::class, 'PSM1PanelStudentList'])->name('panel.studentList');

        Route::post('/assign-PSM1-panel-1', [CoordinatorController::class, 'PSM1SAssignPanel1'])->name('panel1.assign');

        Route::post('/assign-PSM1-panel-2', [CoordinatorController::class, 'PSM1SAssignPanel2'])->name('panel2.assign');

        Route::post('/unassign-PSM1-panel-1/{id}', [CoordinatorController::class, 'PSM1UnassignPSMPanel1'])->name('panel1.unassign');

        Route::post('/unassign-PSM1-panel-2/{id}', [CoordinatorController::class, 'PSM1UnassignPSMPanel2'])->name('panel2.unassign');

        Route::get('/auto-assign-PSM1-panel', [CoordinatorController::class, 'PSM1PredictPanel'])->name('panel.autoAssign');

        Route::get('/remove-ai-suggestions', [CoordinatorController::class, 'removeAllPanelIdsFromPSM1'])->name('panel.removeAi');


        //RURBIC AND CRITERIA

        Route::get('/view-result', [RubricCriteriaController::class, 'PSM1ViewResultCoordinator'])->name('viewResult');

        Route::get('/development-rubric', [RubricCriteriaController::class, 'PSM1DevelopmentRubric'])->name('developmentRubric');

        Route::get('/research-rubric', [RubricCriteriaController::class, 'PSM1ResearchRubric'])->name('researchRubric');

        Route::post('/evaluation-rubric/store', [RubricCriteriaController::class, 'PSM1StoreEvaluationRubric'])->name('evaluationRubric.store');

        Route::put('/evaluation-rubric/{id}/update', [RubricCriteriaController::class, 'PSM1UpdateEvaluationRubric'])->name('evaluationRubric.update');

        Route::delete('/evaluation-rubric/{id}/archive', [RubricCriteriaController::class, 'PSM1ArchiveEvaluationRubric'])->name('evaluationRubric.archive');

        Route::delete('/evaluation-rubric/{id}/delete', [RubricCriteriaController::class, 'PSM1DeleteEvaluationRubric'])->name('evaluationRubric.delete');

        Route::post('/evaluation-rubric/{id}/restore', [RubricCriteriaController::class, 'PSM1RestoreEvaluationRubric'])->name('evaluationRubric.restore');

        Route::post('/evaluation-critertia/store', [RubricCriteriaController::class, 'PSM1StoreEvaluationCriteria'])->name('evaluationCriteria.store');

        Route::put('/evaluation-critertia/{id}/update', [RubricCriteriaController::class, 'PSM1UpdateEvaluationCriteria'])->name('evaluationCriteria.update');

        Route::delete('/evaluation-critertia/{id}/delete', [RubricCriteriaController::class, 'PSM1DeleteEvaluationCriteria'])->name('evaluationCriteria.delete');

        //GRADING

        Route::get('/grade-supervision', [GradingController::class, 'PSM1GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-panel', [GradingController::class, 'PSM1GradePanel'])->name('gradePSM1Panel');

        Route::get('/grade-coordinator', [GradingController::class, 'PSM1GradeCoordinator'])->name('gradePSM1Coordinator');

        Route::post('/store-score', [GradingController::class, 'PSM1StoreScore'])->name('score.store');
        
        Route::delete('/delete-score/{id}', [GradingController::class, 'PSM1DeleteScore'])->name('score.delete');

    });

    Route::prefix('PSM2')
        ->as('PSM2.')
        ->group(function () {

        //STUDENT MANAGEMENT

        Route::get('/list-students', [StudentController::class, 'PSM2ListStudents'])->name('listStudents');

        Route::post('/students/{id}/archive', [StudentController::class, 'PSM2ArchiveStudent'])->name('students.archive');

        Route::post('/students/{id}/restore', [StudentController::class, 'PSM2RestoreStudent'])->name('students.restore');

        Route::delete('/students/{id}/delete', [StudentController::class, 'PSM2DeleteStudent'])->name('students.delete');

        Route::post('/students/store', [StudentController::class, 'PSM2StoreStudent'])->name('students.store');

        Route::put('/students/{id}/update', [StudentController::class, 'PSM2UpdateStudent'])->name('students.update');

        Route::post('/students/import', [StudentController::class, 'PSM2ImportStudent'])->name('students.import');

        Route::post('/students/bulk-archive', [StudentController::class, 'PSM2BulkArchive'])->name('students.bulkArchive');

        Route::get('/students/sample',[StudentController::class, 'getStudentSamplePSM2'] )->name('students.sample');

        //PANEL MANAGEMENT

        Route::get('/list-panels', [PanelController::class, 'PSM2ListPanels'])->name('listPanels');

        Route::post('/panels/{id}/archive', [PanelController::class, 'PSM2ArchivePanel'])->name('panels.archive');

        Route::post('/panels/{id}/restore', [PanelController::class, 'PSM2RestorePanel'])->name('panels.restore');

        Route::delete('/panels/{id}/delete', [PanelController::class, 'PSM2DeletePanel'])->name('panels.delete');

        Route::post('/panels/bulk-archive', [PanelController::class, 'PSM2BulkArchivePanel'])->name('panels.bulkArchive');

        Route::post('/panels/store', [PanelController::class, 'PSM2StorePanel'])->name('panels.store');

        Route::put('/panels/{id}/update', [PanelController::class, 'PSM2UpdatePanel'])->name('panels.update');

        Route::get('/auto-assign-PSM2-panel', [CoordinatorController::class, 'PSM2PredictPanel'])->name('panel.autoAssign');

        Route::get('/remove-ai-suggestions', [CoordinatorController::class, 'removeAllPanelIdsFromPSM2'])->name('panel.removeAi');
        
        //SUPERVISOR ASSIGNMENT

        Route::get('/list-supervisor', [CoordinatorController::class, 'PSM2ListSupervisor'])->name('listSupervisor');

        Route::get('/list-student-supervisor/{id}', [CoordinatorController::class, 'PSM2SupervisorStudentList'])->name('supervisor.studentList');

        Route::post('/request-supervisor', [CoordinatorController::class, 'PSM2RequestSupervisor'])->name('supervisor.request');
        
        Route::post('/cancel-request-supervisor/{id}', [CoordinatorController::class, 'PSM2CancelRequestSupervisor'])->name('supervisor.cancelRequest');

        Route::post('/assign-supervisor', [CoordinatorController::class, 'PSM2SAssignSupervisor'])->name('supervisor.assign');

        Route::post('/unassign-supervisor/{id}', [CoordinatorController::class, 'PSM2UnassignSupervisor'])->name('supervisor.unassign');

        Route::get('/sv-req', [StudentController::class, 'PSM2StudentRequest'])->name('sv.req');

        Route::post('/sv-accept/{id}', [StudentController::class, 'PSM2SAcceptSupervisor'])->name('sv.accept');

        Route::post('/sv-reject/{id}', [StudentController::class, 'PSM2RejectSupervisor'])->name('sv.reject');

        //PANEL PSM2 ASSIGNMENT

        Route::get('/list-panel', [CoordinatorController::class, 'PSM2ListPanel'])->name('listPSM2Panel');
        
        Route::get('/list-student-panel-PSM2', [CoordinatorController::class, 'PSM2PanelStudentList'])->name('panel.studentList');

        Route::post('/assign-PSM2-panel-1', [CoordinatorController::class, 'PSM2SAssignPanel1'])->name('panel1.assign');

        Route::post('/assign-PSM2-panel-2', [CoordinatorController::class, 'PSM2SAssignPanel2'])->name('panel2.assign');

        Route::post('/unassign-PSM2-panel-1/{id}', [CoordinatorController::class, 'PSM2UnassignPSMPanel1'])->name('panel1.unassign');

        Route::post('/unassign-PSM2-panel-2/{id}', [CoordinatorController::class, 'PSM2UnassignPSMPanel2'])->name('panel2.unassign');

        //RUBRIC AND CRITERIA

        Route::get('/view-result', [RubricCriteriaController::class, 'PSM2ViewResultCoordinator'])->name('viewResult');

        Route::get('/development-rubric', [RubricCriteriaController::class, 'PSM2DevelopmentRubric'])->name('developmentRubric');

        Route::get('/research-rubric', [RubricCriteriaController::class, 'PSM2ResearchRubric'])->name('researchRubric');

        Route::post('/evaluation-rubric/store', [RubricCriteriaController::class, 'PSM2StoreEvaluationRubric'])->name('evaluationRubric.store');

        Route::put('/evaluation-rubric/{id}/update', [RubricCriteriaController::class, 'PSM2UpdateEvaluationRubric'])->name('evaluationRubric.update');

        Route::delete('/evaluation-rubric/{id}/archive', [RubricCriteriaController::class, 'PSM2ArchiveEvaluationRubric'])->name('evaluationRubric.archive');

        Route::delete('/evaluation-rubric/{id}/delete', [RubricCriteriaController::class, 'PSM2DeleteEvaluationRubric'])->name('evaluationRubric.delete');

        Route::post('/evaluation-rubric/{id}/restore', [RubricCriteriaController::class, 'PSM2RestoreEvaluationRubric'])->name('evaluationRubric.restore');

        Route::post('/evaluation-critertia/store', [RubricCriteriaController::class, 'PSM2StoreEvaluationCriteria'])->name('evaluationCriteria.store');

        Route::put('/evaluation-critertia/{id}/update', [RubricCriteriaController::class, 'PSM2UpdateEvaluationCriteria'])->name('evaluationCriteria.update');

        Route::delete('/evaluation-critertia/{id}/delete', [RubricCriteriaController::class, 'PSM2DeleteEvaluationCriteria'])->name('evaluationCriteria.delete');

        //GRADING

        Route::get('/grade-supervision', [GradingController::class, 'PSM2GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-panel', [GradingController::class, 'PSM2GradePanel'])->name('gradePSM2');

        Route::get('/grade-coordinator', [GradingController::class, 'PSM2GradeCoordinator'])->name('gradePSM2Coordinator');

        Route::post('/store-score', [GradingController::class, 'PSM2StoreScore'])->name('score.store');
        
        Route::delete('/delete-score/{id}', [GradingController::class, 'PSM2DeleteScore'])->name('score.delete');

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

        Route::get('/grade-supervision', [GradingController::class, 'PSM1GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-panel', [GradingController::class, 'PSM1GradePanel'])->name('gradePSM1');

        Route::post('/store-score', [GradingController::class, 'PSM1StoreScore'])->name('score.store');

        Route::delete('/delete-score/{id}', [GradingController::class, 'PSM1DeleteScore'])->name('score.delete');

        Route::get('/view-result', [RubricCriteriaController::class, 'PSM1ViewResultPanel'])->name('viewResult');

        Route::get('/sv-req', [StudentController::class, 'PSM1StudentRequest'])->name('sv.req');

        Route::post('/sv-accept/{id}', [StudentController::class, 'PSM1SAcceptSupervisor'])->name('sv.accept');

        Route::post('/sv-reject/{id}', [StudentController::class, 'PSM1RejectSupervisor'])->name('sv.reject');

    });

    Route::prefix('PSM2')
        ->as('PSM2.')
        ->group(function () {

        Route::get('/grade-supervision', [GradingController::class, 'PSM2GradeSupervision'])->name('gradeSupervision');

        Route::get('/grade-panel', [GradingController::class, 'PSM2GradePanel'])->name('gradePSM2');

        Route::post('/store-score', [GradingController::class, 'PSM2StoreScore'])->name('score.store');

        Route::get('/view-result', [RubricCriteriaController::class, 'PSM2ViewResultPanel'])->name('viewResult');
        
        Route::delete('/delete-score/{id}', [GradingController::class, 'PSM2DeleteScore'])->name('score.delete');

        Route::post('/students/{matric}/project-progress/', [StudentController::class, 'PSM2ProjectProgress'])->name('students.projectProgress');

        Route::get('/sv-req', [StudentController::class, 'PSM2StudentRequest'])->name('sv.req');

        Route::post('/sv-accept/{id}', [StudentController::class, 'PSM2SAcceptSupervisor'])->name('sv.accept');

        Route::post('/sv-reject/{id}', [StudentController::class, 'PSM2RejectSupervisor'])->name('sv.reject');

    });
});

