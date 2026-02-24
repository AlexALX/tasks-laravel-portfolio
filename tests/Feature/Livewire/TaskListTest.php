<?php

namespace Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use App\Models\Task;

class TaskListTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_component_renders()
    {
        Livewire::test('task-list')
            ->assertStatus(200);
    }

    public function test_search_can_find_task()
    {
        $task = Task::factory()->create();

        Livewire::test('task-list')
            ->set('search', $task->title)
            ->assertSee($task->title);
    }

    public function test_search_with_invalid_title()
    {
        $title = $this->faker->words(3, true);

        Livewire::test('task-list')
            ->set('search', $title)
            ->assertDontSee($title);
    }
}
