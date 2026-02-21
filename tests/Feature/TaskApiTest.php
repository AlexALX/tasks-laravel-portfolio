<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use refreshDatabase, WithFaker;

    public function test_can_get_tasks_list(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson(route('tasks.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_task(): void
    {
        $response = $this->postJson(route('tasks.store'), [
            'title' => $this->faker->words(3,true),
            'description' => $this->faker->sentence(),
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(6, 'data');
    }

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson(route('tasks.update', $task->id), [
            'title' => $this->faker->words(3,true),
            'description' => $this->faker->sentence(),
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data');
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertStatus(204);
    }

    public function test_cant_create_with_invalid_data(): void
    {
        $response = $this->postJson(route('tasks.store'));

        $response->assertStatus(422);
    }

    public function test_cant_update_with_invalid_id(): void
    {
        $response = $this->putJson(route('tasks.update', ['task' => 0]), [
            'title' => '',
        ]);

        $response->assertStatus(404);
    }
}
