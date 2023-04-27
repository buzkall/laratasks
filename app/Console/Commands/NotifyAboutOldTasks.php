<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskNotification;
use Illuminate\Console\Command;

class NotifyAboutOldTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laratasks:notify-about-old-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify the user about old tasks';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tasks = Task::where('updated_at', '<=', now()->subDays(7))
                     ->whereNull('completed_at')
                     ->get();

        $tasks
            ->each(fn($task) => $task->user->notify(new TaskNotification($task)));
    }
}
