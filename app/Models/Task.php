<?php

namespace App\Models;

use App\Mail\TaskCreated;
use App\Traits\HasTags;
use App\Traits\TraitFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;

class Task extends Model
{
    use HasFactory;
    use HasTags;
    use TraitFilters;
    use Prunable;

    protected $fillable = ['user_id', 'title', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime'
    ];


    public function prunable(): Builder
    {
        return static::where('completed_at', '<=', now()->subMonth());
    }

    protected static function booted(): void
    {
        static::created(function (Task $task) {
            Mail::to('mail@mail.com')
                ->send(new TaskCreated($task));
        });

        static::updated(function (Task $task) {
            if ($task->isDirty('completed_at') && $task->completed_at !== null) {
                Mail::to('mail@mail.com')
                    ->send(new TaskCreated($task));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)
                    ->withDefault(['name' => 'Sin usuario']);
    }
}
