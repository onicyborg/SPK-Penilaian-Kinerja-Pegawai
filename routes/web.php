<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentPeriodeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\AssessmentResultController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'],function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/update-password', [AuthController::class, 'updatePassword'])->name('profile.update-password');

    // Assessment Periode CRUD
    Route::get('assessment-periode', [AssessmentPeriodeController::class, 'index'])->name('assessment-periode.index');
    Route::post('assessment-periode/store', [AssessmentPeriodeController::class, 'store'])->name('assessment-periode.store');
    Route::put('assessment-periode/update/{id}', [AssessmentPeriodeController::class, 'update'])->name('assessment-periode.update');
    Route::delete('assessment-periode/delete/{id}', [AssessmentPeriodeController::class, 'destroy'])->name('assessment-periode.destroy');

    Route::get('/master-data-karyawan', [EmployeesController::class, 'index'])->name('master-data-karyawan');
    Route::post('/master-data-karyawan', [EmployeesController::class, 'store'])->name('master-data-karyawan.store');
    Route::get('/master-data-karyawan/{id}', [EmployeesController::class, 'getEmployee'])->name('master-data-karyawan.get');
    Route::put('/master-data-karyawan/{id}', [EmployeesController::class, 'update'])->name('master-data-karyawan.update');
    Route::delete('/master-data-karyawan/{id}', [EmployeesController::class, 'destroy'])->name('master-data-karyawan.delete');

    // CRUD Assessor
    Route::get('manage-assessor', [\App\Http\Controllers\ManageAssessorController::class, 'index'])->name('manage-assessor.index');
    Route::get('manage-assessor/data', [\App\Http\Controllers\ManageAssessorController::class, 'data'])->name('manage-assessor.data');
    Route::post('manage-assessor', [\App\Http\Controllers\ManageAssessorController::class, 'store'])->name('manage-assessor.store');
    Route::put('manage-assessor/{id}', [\App\Http\Controllers\ManageAssessorController::class, 'update'])->name('manage-assessor.update');
    Route::delete('manage-assessor/{id}', [\App\Http\Controllers\ManageAssessorController::class, 'destroy'])->name('manage-assessor.destroy');

    Route::get('assessment/setting-criteria/{id}', [CriteriaController::class, 'index'])->name('assessment.setting-criteria');
    Route::post('assessment/setting-criteria/{id}', [CriteriaController::class, 'store'])->name('assessment.setting-criteria.store');

    Route::get('assessment/assessment-employee/{id}', [AssessmentController::class, 'index'])->name('assessment.assessment-employee');
    Route::post('assessment/assessment-employee/{id}/store', [AssessmentController::class, 'store'])->name('assessment.assessment-employee.store');


    Route::post('assessment/store/{id}', [AssessmentController::class, 'store'])->name('assessment.store');

    Route::post('assessment/store/{id}/saw', [AssessmentController::class, 'processSAW'])->name('assessment.store.saw');

    Route::get('assessment/results/{id}', [AssessmentResultController::class, 'results'])->name('assessment.results');
    Route::get('assessment/logs/{id}', [\App\Http\Controllers\AssessmentLogsController::class, 'index'])->name('assessment.logs');
});
