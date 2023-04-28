<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $task, public $count = 1)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('hey')
            ->line(trans_choice('You have :count task pending', $this->count, ['count' => $this->count]))
            ->action('Revísala', route('tasks.edit', $this->task))
            ->line('Gracias!')
            ->salutation('bye');
    }

    public function toDatabase(object $notifiable)
    {
        return ['task_id' => $this->task->id,
                'count' => $this->count];
    }
}
