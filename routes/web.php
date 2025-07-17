<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;


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
    Route::get('students', [StudentController::class, 'index'])->name('index');
    Route::post('students', [StudentController::class, 'addStudent'])->name('store');
    Route::get('students/{student}', [StudentController::class, 'viewStudent'])->name('view');
    Route::put('students/{student}', [StudentController::class, 'updateStudent'])->name('update');
    Route::delete('students/{student}', [StudentController::class, 'deleteStudent'])->name('delete');
});

Route::middleware(['auth'])->name('course.')->group(function () {
    Route::get('course', [CourseController::class, 'index'])->name('index');
    Route::post('course', [CourseController::class, 'addCourse'])->name('store');
    Route::get('course/{subject}', [CourseController::class, 'viewCourse'])->name('view');
    Route::put('course/{subject}', [CourseController::class, 'updateCourse'])->name('update');
    Route::delete('course/{subject}', [CourseController::class, 'deleteCourse'])->name('delete');
});

Route::middleware(['auth'])->name('exams.')->group(function () {
    Route::get('exams/schedule', [ExamController::class, 'schedule'])->name('schedule');
    Route::post('exams/store', [ExamController::class, 'addSchedule'])->name('scheduleStore');
    Route::get('exams/mark', [ExamController::class, 'marks'])->name('mark');
    Route::post('exams/mark', [ExamController::class, 'storeMark'])->name('store');
    Route::put('exams/{student}', [ExamController::class, 'updateMark'])->name('update');
    Route::delete('exams/{student}', [ExamController::class, 'deleteMark'])->name('delete');
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
