<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    public function index()
    {
        $tasks = Task::with(['user:id,name', 'tags:id,name'])
                     ->when(! auth()->user()->isAdmin, fn($query) => $query->whereBelongsTo(auth()->user()))
                     ->orderByDesc('id')
                     ->paginate(config('laratasks.pagination'));

        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function store(TaskRequest $request)
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')
                         ->with('message', __('Task created successfully'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', ['task' => $task]);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')
                         ->with('message', __('Task updated successfully'));
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
                         ->with('message', __('Task deleted successfully'));
    }
}
