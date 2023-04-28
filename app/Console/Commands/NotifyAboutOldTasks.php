<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskNotification;
use Illuminate\Console\Command;

class NotifyAboutOldTasks extends Command
{
    protected $signature = 'laratasks:notify-about-old-tasks {--summary : Send just one email }';

    protected $description = 'Notify the user about old tasks';

    public function handle(): void
    {
        $tasks = Task::where('updated_at', '<=', now()->subDays(7))
                     ->whereNull('completed_at')
                     ->get();

        if ($this->option('summary')) {
            $tasksGroupedByUser = $tasks->groupBy('user_id');
            $users = User::findMany($tasksGroupedByUser->keys());

            foreach ($users as $user) {
                $userTasks = $tasksGroupedByUser->get($user->id);
                $user->notify(new TaskNotification($userTasks->first(), $userTasks->count()));
            }
        } else {
            $tasks
                ->each(fn($task) => $task->user->notify(new TaskNotification($task)));
        }
    }
}
