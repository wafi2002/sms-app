<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect('/login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth'])->name('students.')->group(function () {
    Route::get('students', [App\Http\Controllers\StudentController::class, 'index'])->name('index');
    Route::post('students', [App\Http\Controllers\StudentController::class, 'addStudent'])->name('store');
    Route::get('students/{student}', [App\Http\Controllers\StudentController::class, 'viewStudent'])->name('view');
    Route::put('students/{student}', [App\Http\Controllers\StudentController::class, 'updateStudent'])->name('update');
    Route::delete('students/{student}', [App\Http\Controllers\StudentController::class, 'deleteStudent'])->name('delete');
});

Route::middleware(['auth'])->name('course.')->group(function () {
    Route::view('course/all-courses', 'modules.courseModule.index')->name('index');
});

Route::middleware(['auth'])->name('exams.')->group(function () {
    Route::view('exams/schedule', 'modules.examModule.schedule')->name('schedule');
    Route::get('exams/mark', [App\Http\Controllers\ExamController::class, 'marks'])->name('mark');
    Route::put('exams/{student}', [App\Http\Controllers\ExamController::class, 'updateMark'])->name('update');
    Route::delete('exams/{student}', [App\Http\Controllers\ExamController::class, 'deleteMark'])->name('delete');
});

Route::middleware(['auth'])->name('reports.')->group(function () {
    Route::get('reports/student', [ReportController::class, 'averageStudent'])->name('student');
    Route::get('reports/average-student', [ReportController::class, 'exportStudentAvgExcel'])->name('exportStdAvgExcel');
    Route::get('reports/average-student-pdf', [ReportController::class, 'exportStudentAvgPDF'])->name('exportStdAvgPdf');

    Route::get('reports/subject', [ReportController::class, 'averageSubject'])->name('subject');
    Route::get('reports/average-subject', [ReportController::class, 'exportSubjectAvgExcel'])->name('exportSbjAvgExcel');
    Route::get('reports/average-subject-pdf', [ReportController::class, 'exportSubjectAvgPDF'])->name('exportSbjAvgPdf');
});
require __DIR__ . '/auth.php';
