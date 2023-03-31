<?php

namespace App\Http\Controllers;

use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function show(Task $task)
    {
        return view('tasks.show', ['task' => $task]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store()
    {
        $data = request()->validate(['title' => 'required|min:3']);

        Task::create($data);

        return redirect()->route('tasks.index');
    }
}
