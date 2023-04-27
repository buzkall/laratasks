<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    public function complete(Task $task)
    {
        $this->authorize('complete', $task);

        $task->update(['completed_at' => now()]);

        return response()->json([
            'message' => __('Task completed successfully')
        ]);
    }
}
