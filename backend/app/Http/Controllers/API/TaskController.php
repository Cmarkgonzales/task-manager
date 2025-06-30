<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ReorderTasksRequest;
use App\Events\TaskCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $search = $request->search ?? '';
        $status = $request->status ?? '';
        $priority = $request->priority ?? '';

        $cacheKey = "tasks_user_{$userId}_search_{$search}_status_{$status}_priority_{$priority}";

        $tasks = Cache::remember($cacheKey, 60, function () use ($request, $userId) {
            return Task::withoutTrashed()
                ->where('user_id', $userId)
                ->when($request->status, fn($q) => $q->status($request->status))
                ->when($request->priority, fn($q) => $q->priority($request->priority))
                ->when($request->search, fn($q) =>
                    $q->where(function ($query) use ($request) {
                        $query->where('title', 'like', "%{$request->search}%")
                            ->orWhere('description', 'like', "%{$request->search}%");
                    })
                )
                ->orderBy('order')
                ->get();
        });

        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $user = $request->user();
        $nextOrder = Task::where('user_id', $user->id)->max('order') + 1;
        $task = Task::create(array_merge(
            $request->validated(),
            [
                'user_id' => $request->user()->id,
                'order'   => $nextOrder,
            ]
        ));

        Cache::forget("tasks_user_{$request->user()->id}");

        // Fire the broadcast event
        broadcast(new TaskCreated($task))->toOthers();

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        Cache::forget("tasks_user_{$request->user()->id}");

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        Cache::forget("tasks_user_{$task->user_id}");

        return response()->json(['message' => 'Task deleted']);
    }

    public function reorder(ReorderTasksRequest $request)
    {
        $user = $request->user();
        $taskIds = $request->ordered_ids;

        // Fetch only the user's tasks to avoid updating others'
        $tasks = Task::where('user_id', $user->id)
            ->whereIn('id', $taskIds)
            ->get()
            ->keyBy('id');

        if ($tasks->count() !== count($taskIds)) {
            return response()->json([
                'error' => 'Some tasks do not belong to the authenticated user or do not exist.'
            ], 403);
        }

        // Transaction ensures consistency
        DB::transaction(function () use ($taskIds, $user) {
            foreach ($taskIds as $index => $id) {
                Task::where('id', $id)
                    ->where('user_id', $user->id)
                    ->update(['order' => $index]);
            }
        });

        Cache::forget("tasks_user_{$user->id}");

        return response()->json(['message' => 'Tasks reordered successfully']);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        // If status is explicitly provided, validate it
        if ($request->has('status')) {
            $validated = $request->validate([
                'status' => 'required|in:pending,completed',
            ]);

            $task->update(['status' => $validated['status']]);
        } else {
            // Otherwise, toggle status
            $newStatus = $task->status === 'completed' ? 'pending' : 'completed';
            $task->update(['status' => $newStatus]);
        }

        Cache::forget("tasks_user_{$task->user_id}");

        return new TaskResource($task);
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        $baseQuery = Task::withoutTrashed()->where('user_id', $user->id);

        return response()->json([
            'total'     => $baseQuery->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
        ]);
    }
}
