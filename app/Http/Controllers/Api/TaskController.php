<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TaskResource::collection(Task::latest()->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $taskRequest)
    {
        $validated = $taskRequest->validated();
        return new TaskResource(Task::create($validated));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $taskRequest, Task $task)
    {
        $validated = $taskRequest->validated();
        $task->update($validated);
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
