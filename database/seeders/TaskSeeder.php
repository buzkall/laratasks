<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::factory(5)
            ->for(User::factory()->hasTags()->create())
            ->hasTags(rand(0,3))
            ->create();

        Task::factory(5)
            ->for(User::factory()->hasTags()->create())
            ->hasTags(rand(0,3))
            ->completed()
            ->create();
    }
}
