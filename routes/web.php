<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('tasks', TaskController::class)->except(['show', 'create']);
Route::put('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
