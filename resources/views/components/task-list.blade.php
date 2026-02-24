<?php

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    protected $listeners = ['task-added' => 'updateTasks'];

    public $search = '';

    public function delete($taskId): void
    {
        Task::destroy($taskId);
    }

    public function with(): array
    {
        return [
            'tasks' => Task::latest()->where('title', 'LIKE', '%'.$this->search.'%')->paginate(5),
        ];
    }
}
?>

<div class="mt-4 w-full max-w-md mx-auto p-4 bg-white shadow rounded" x-data="{
        deleteConfirm: 0,
    }">

    @if (count($tasks) || $search)
        <div>
            <input placeholder="Search Task" wire:model.live.debounce="search" class="w-full ring-2 ring-emerald-200 hover:ring-emerald-400 focus:ring-emerald-400 p-2 rounded outline-none">
        </div>
    @endif

    @if ($tasks->count())
    <div class="flex flex-col space-y-2 my-2">
        @foreach($tasks as $task)
            <div class="relative" wire:loading.class="opacity-50 pointer-events-none">
                <div class="text-emerald-400 font-bold">
                    <span class="cursor-pointer" wire:click="$dispatch('task-edit', '{{ $task->id }}')"><span class="text-sm">🖉</span> {{ $task->title }}</span>

                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs
                        font-medium @if ($task->completed) bg-emerald-100 text-emerald-800 @else bg-yellow-100 text-yellow-800 @endif"
                    >{{ $task->completed ? 'Completed' : 'Incomplete' }}</span>

                </div>
                <div class="text-gray-400 text-sm">{{ $task->description }}</div>
                <div class="text-red-400 absolute right-0 top-1/2 -translate-y-1/2 cursor-pointer"
                     @click="deleteConfirm = {{$task->id}}">X
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{ $tasks->links() }}

    @if (!$tasks->count())
        <div wire:loading.class="opacity-50 pointer-events-none"  @if ($search) class="mt-2" @endif>
            @if ($search)
                No Tasks found.
            @else
                No Tasks created yet.
            @endif
        </div>
    @endif

    <div x-cloak class="fixed inset-0 z-5" x-show="deleteConfirm>0" x-transition.opacity>
        <div class="fixed inset-0 bg-black/25"></div>

        <div class="relative min-h-screen flex items-center justify-center p-3">
            <div class="relative sm:min-w-100 max-w-xl rounded bg-white p-4 shadow">
                <div class="font-medium text-red-400">Confirm Deletion</div>
                <div class="mt-2 text-gray-600">Are you sure you want to delete this task?</div>
                <div class="mt-4 text-center space-x-2">
                    <button @click="$wire.delete(deleteConfirm); deleteConfirm = 0" type="button" class="cursor-pointer transition disabled:bg-red-200 bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                    <button @click="deleteConfirm = 0" type="button" class="cursor-pointer transition disabled:bg-gray-200 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>
