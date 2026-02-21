<?php

namespace Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TaskFormTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_component_renders()
    {
        Livewire::test('task-form')
            ->assertStatus(200);
    }

    public function test_livewire_can_create_task()
    {
        $title = $this->faker->words(3, true);

        Livewire::test('task-form')
            ->set('title', $title)
            ->call('submit');

        $this->assertDatabaseHas('tasks', [
            'title' => $title,
        ]);
    }

    public function test_livewire_validation_errors()
    {
        Livewire::test('task-form')
            ->set('title', '')
            ->call('submit')
            ->assertHasErrors(['title']);
    }
}
