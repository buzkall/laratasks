<?php

use App\Mail\TaskCreated;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskNotification;

beforeEach(function () {
    $this->admin = User::factory()->hasRoles(['name' => 'admin'])->create();
    $this->user = User::factory()->create();
});

test('guest user cannot see tasks', function () {
    Task::factory(10)->create();

    $this->get(route('tasks.index'))
         ->assertRedirect(route('login'));
});

test('admin sees all the tasks', function () {
    $this->actingAs($this->admin);
    $tasks = Task::factory(10)->create();

    $this->get(route('tasks.index'))
         ->assertStatus(200)
         ->assertSee(__('Tasks'))
         ->assertSee($tasks->first()->title);
});

test('user sees only his tasks', function () {
    $this->actingAs($this->user);
    $taskAdmin = Task::factory()->for($this->admin)->create();
    $taskUser = Task::factory()->for($this->user)->create();

    $this->get(route('tasks.index'))
         ->assertStatus(200)
         ->assertSee(__('Tasks'))
         ->assertSee($taskUser->title)
         ->assertDontSee($taskAdmin->title);
});

it('shows the diff time for a completed task', function () {
    $this->actingAs($this->admin);

    $delay = random_int(1, 59);
    $time = now()->subMinutes($delay);
    Task::factory()->create(['completed_at' => $time]);

    $this->get(route('tasks.index'))
         ->assertStatus(200)
         ->assertSee('hace ' . $delay . ' minutos');
});

it('throws un error when trying to create a task without title', function () {
    $this->actingAs($this->admin);

    $this->post(route('tasks.store'), ['title' => ''])
         ->assertSessionHasErrors('title')
         ->assertSessionHas('errors', function ($errors) {
             return $errors->has('title') && $errors->first('title') == 'El campo título es obligatorio.';
         });
});

it('throws un error when trying to create a task with less than 3 characters title', function () {
    $this->actingAs($this->admin);

    $title = str_repeat('a', config('laratasks.min_title_length') - 1);
    $this->post(route('tasks.store'), ['title' => $title])
         ->assertSessionHasErrors('title')
         ->assertSessionHas('errors', function ($errors) {
             return $errors->has('title') &&
                    $errors->first('title') == 'El campo título debe contener al menos ' . config('laratasks.min_title_length') . ' caracteres.';
         });
});

it('throws un error when trying to create a task with duplicated title', function () {
    $this->actingAs($this->admin);

    $title = fake()->sentence(3);
    Task::factory()->create(['title' => $title]);

    $this->post(route('tasks.store'), ['title' => $title])
         ->assertSessionHasErrors('title')
         ->assertSessionHas('errors', function ($errors) {
             return $errors->has('title') &&
                    $errors->first('title') == 'El valor del campo título ya está en uso.';
         });
});

it('creates a task with title', function () {
    $this->actingAs($this->admin);

    $this->assertDatabaseEmpty('tasks');

    $title = fake()->sentence(3);
    $this->post(route('tasks.store', ['title' => $title]))
         ->assertSessionHasNoErrors()
         ->assertRedirect(route('tasks.index'))
         ->assertSessionHas('message', __('Task created successfully'));

    $this->assertDatabaseCount('tasks', 1);
    $this->assertDatabaseHas('tasks', ['title' => $title,
                                       'user_id' => $this->admin->id]);
});

it('admin deletes a task', function () {
    $this->actingAs($this->admin);

    $this->assertDatabaseCount('tasks', 0);
    $task = Task::factory()->create();

    $this->assertDatabaseCount('tasks', 1);

    $this->delete(route('tasks.destroy', $task))
         ->assertSessionHasNoErrors()
         ->assertRedirect(route('tasks.index'))
         ->assertSessionHas('message', __('Task deleted successfully'));

    $this->assertDatabaseCount('tasks', 0);
});

it('user cannot delete a task', function () {
    $this->actingAs($this->user);

    $task = Task::factory()->create();

    $this->delete(route('tasks.destroy', $task))
         ->assertStatus(403);
});

it('completes a task', function () {
    $task = Task::factory()->create();

    $this->freezeSecond();

    $this->put(route('api.tasks.complete', $task))
         ->assertOk()
         ->assertExactJson([
                               'message' => __('Task completed successfully')
                           ]);

    $task->refresh();
    $this->assertEquals($task->completed_at, now());
});

it('sends an email after creating a task', function () {
    Mail::fake();
    Task::factory()->create();

    Mail::assertSent(TaskCreated::class, function ($mail) {
        return $mail->hasTo('mail@mail.com');
    });
});

test('command sends notifications of old task to user', function () {
    Notification::fake();
    $times = rand(1, 5);
    Task::factory($times)->for($this->user)->create();

    $this->artisan('laratasks:notify-about-old-tasks')
         ->assertExitCode(0);
    Notification::assertCount(0);

    $this->travelTo(now()->addDays(7)->addMinute());
    $this->artisan('laratasks:notify-about-old-tasks')
         ->assertExitCode(0);
    Notification::assertSentTimes(TaskNotification::class, $times);

    Notification::assertSentTo([$this->user], TaskNotification::class);
});

test('command can group notifications of 1 old task to user', function () {
    Notification::fake();
    $times = 1;
    Task::factory($times)->for($this->user)->create();

    $this->travelTo(now()->addDays(7)->addMinute());
    $this->artisan('laratasks:notify-about-old-tasks --summary')
         ->assertExitCode(0);
    Notification::assertSentTimes(TaskNotification::class, $times);

    Notification::assertSentTo([$this->user], TaskNotification::class,
        function ($notification, $channels, $notifiable) {
            $mailData = $notification->toMail($notifiable);

            $this->assertStringContainsString('Tienes 1 tarea pendiente', $mailData->render());

            return true;
        });
});

test('command can group notifications of more than one old task to user', function () {
    Notification::fake();
    $times = rand(2,5);
    Task::factory($times)->for($this->user)->create();

    $this->travelTo(now()->addDays(7)->addMinute());
    $this->artisan('laratasks:notify-about-old-tasks --summary')
         ->assertExitCode(0);

    Notification::assertSentTimes(TaskNotification::class, 1);

    Notification::assertSentTo([$this->user], TaskNotification::class,
        function ($notification, $channels, $notifiable) use ($times) {
            $mailData = $notification->toMail($notifiable);

            $this->assertStringContainsString('Tienes '.$times.' tareas pendientes', $mailData->render());

            return true;
        });
});
