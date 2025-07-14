<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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
    Route::get('/students', [App\Http\Controllers\StudentController::class, 'index'])->name('index');
    Route::post('/students', [App\Http\Controllers\StudentController::class, 'addStudent'])->name('store');
    Route::get('/students/{student}', [App\Http\Controllers\StudentController::class, 'viewStudent'])->name('view');
    Route::patch('/students/{student}', [App\Http\Controllers\StudentController::class, 'updateStudent'])->name('update');
    Route::delete('/students/{student}', [App\Http\Controllers\StudentController::class, 'deleteStudent'])->name('delete');
});

Route::middleware(['auth'])->name('course.')->group(function () {
    Route::view('course/all-courses', 'modules.courseModule.index')->name('index');
});

Route::middleware(['auth'])->name('exams.')->group(function () {
    Route::view('exams/schedule', 'modules.examModule.schedule')->name('schedule');
    Route::view('exams/mark', 'modules.examModule.mark')->name('mark');
});

Route::middleware(['auth'])->name('reports.')->group(function () {
    Route::view('reports/student', 'modules.reportModule.averageStudent')->name('student');
    Route::view('reports/subject', 'modules.reportModule.averageSubject')->name('subject');
});
require __DIR__ . '/auth.php';
