<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class PruneCompletedOldTasks extends Command
{
    protected $signature = 'laratasks:prune-completed-old-tasks';

    protected $description = 'Delete old tasks that have been completed';

    public function handle(): void
    {
        Task::where('completed_at', '<', now()->subDays(30))->delete();
    }
}
