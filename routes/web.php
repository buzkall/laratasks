<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
