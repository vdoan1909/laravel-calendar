<?php

use App\Http\Controllers\LecturerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::prefix('lecturer')
    ->name('lecturer.')
    ->middleware(['auth', 'lecturer'])
    ->group(function () {
        Route::withoutMiddleware('lecturer')
            ->group(function () {
                Route::get('/schedule/students', [LecturerController::class, 'scheduleListForStudent'])->name('scheduleListForStudent');
                Route::get('/', [LecturerController::class, 'index'])->name('index');
                Route::post('/join-schedule', [LecturerController::class, 'joinSchedule'])->name('join.schedule');
            });

        Route::get('/schedules', [LecturerController::class, 'schedules'])->name('schedule');
        Route::post('/', [LecturerController::class, 'store'])
            ->name('schedule.store');
        Route::patch('/{id}', [LecturerController::class, 'update'])
            ->name('schedule.update');
    });

Route::get('/', function () {
    return view('welcome');
})->name('student');