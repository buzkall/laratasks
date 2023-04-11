<?php

use App\Models\Task;

it('lists all the tasks', function () {
    $tasks = Task::factory(10)->create();

    $this->get(route('tasks.index'))
         ->assertStatus(200)
         ->assertSee(__('Tasks'))
         ->assertSee($tasks->first()->title);
});

it('shows the diff time for a completed task', function () {
    $delay = random_int(1, 59);
    $time = now()->subMinutes($delay);
    Task::factory()->create(['completed_at' => $time]);

    $this->get(route('tasks.index'))
         ->assertStatus(200)
         ->assertSee('hace ' . $delay . ' minutos');
});

it('throws un error when trying to create a task without title', function () {
    $this->post(route('tasks.store'), ['title' => ''])
         ->assertSessionHasErrors('title')
         ->assertSessionHas('errors', function ($errors) {
             return $errors->has('title') && $errors->first('title') == 'El campo título es obligatorio.';
         });
});

it('throws un error when trying to create a task with less than 3 characters title', function () {
    $title = str_repeat('a', config('laratasks.min_title_length') - 1);
    $this->post(route('tasks.store'), ['title' => $title])
         ->assertSessionHasErrors('title')
         ->assertSessionHas('errors', function ($errors) {
             return $errors->has('title') &&
                    $errors->first('title') == 'El campo título debe contener al menos ' . config('laratasks.min_title_length') . ' caracteres.';
         });
});

it('throws un error when trying to create a task with duplicated title', function () {
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
    $this->assertDatabaseEmpty('tasks');

    $title = fake()->sentence(3);
    $this->post(route('tasks.store', ['title' => $title]))
         ->assertSessionHasNoErrors()
         ->assertRedirect(route('tasks.index'))
         ->assertSessionHas('message', __('Task created successfully'));

    $this->assertDatabaseCount('tasks', 1);
    $this->assertDatabaseHas('tasks', ['title' => $title]);
});
