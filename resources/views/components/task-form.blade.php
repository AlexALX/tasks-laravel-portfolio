<?php

use App\Models\Task;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('boolean')]
    public bool $completed = false;

    public int $taskId = 0;

    protected $listeners = ['task-edit' => 'editTask'];

    public function submit()
    {
        $validated = $this->validate();

        if ($this->taskId) {
            Task::findOrFail($this->taskId)->update($validated);
        } else {
            Task::create($validated);
        }

        $this->clearForm();
        $this->dispatch('task-added');
    }

    public function editTask($taskId) {

        $task = Task::findOrFail($taskId);

        $this->title = $task->title;
        $this->description = $task->description;
        $this->taskId = $task->id;

        $this->completed = $task->completed;
    }

    public function clearForm() {
        $this->title = '';
        $this->description = '';
        $this->taskId = 0;
        $this->completed = false;
    }
};
?>

<div class="w-full max-w-md mx-auto p-4 bg-white shadow rounded">
    <form wire:submit.prevent="submit" class="space-y-6">

        <div>
            <label>Title: <span class="text-red-500">*</span></label>
            <input wire:model="title" type="text"
                   class="transition w-full ring-2 ring-emerald-200 hover:ring-emerald-400 focus:ring-emerald-400 p-2 rounded outline-none">
            @error('title')
            <div class="text-red-500">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>Description:</label>
            <textarea wire:model="description"
                      class="transition w-full ring-2 ring-emerald-200 hover:ring-emerald-400 focus:ring-emerald-400 p-2 rounded outline-none"></textarea>
            @error('description')
            <div class="text-red-500">{{ $message }}</div>@enderror
        </div>

        <div x-data="{
            checked: @entangle('completed'),
        }" class="relative">
            <label>Completed:</label>
            <label class="toggle-switch" :class="checked ? 'active' : 'inactive'">
                <span class="toggle-thumb" :class="checked ? 'translate-x-5.5' : 'translate-x-0.5'"></span>
                <input type="checkbox" wire:model="completed" class="hidden">
            </label>
        </div>

        <div class="text-center">
            <button type="submit" wire:loading.attr="disabled"
                    class="cursor-pointer transition disabled:bg-emerald-200 bg-emerald-500 text-white px-4 py-2 rounded">
                @if ($this->taskId) Edit Task @else Add Task @endif
            </button>
            @if ($this->taskId)
                <button wire:click.prevent="clearForm" wire:loading.attr="disabled"
                        class="ml-2 cursor-pointer transition disabled:bg-gray-200 bg-gray-500 text-white px-4 py-2 rounded">
                    Cancel
                </button>
            @endif
        </div>
    </form>
</div>
