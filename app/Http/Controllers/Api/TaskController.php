<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    // List tasks for the authenticated user
    public function index(): JsonResponse
    {
        // Get optional query parameters
        $status = request()->query('status'); // pending, in-progress, completed
        $perPage = (int) request()->query('per_page', 10); // default 10 per page

        $query = auth()->user()->tasks()->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $tasks = $query->paginate($perPage);

        return response()->json($tasks);
    }

    // Create a new task
    public function store(TaskRequest $request): JsonResponse
    {
        $task = auth()->user()->tasks()->create($request->validated());
        Log::info('Task created:', [$task]);
        return response()->json($task, 201);
    }

    // Show a single task
    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    // Update a task
    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    // Delete a task
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        Log::info('Task soft deleted', [
            'task_id' => $task->id,
            'user_id' => auth()->id(),
        ]);

        return response()->json(null, 204);
    }

    /* Ensure the task belongs to the authenticated user
    private function authorizeTask(Task $task): void
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
    }
        */
}
