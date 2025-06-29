<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $perPage  = $request->get('per_page', 10);
        $search   = $request->get('search');
        $sortBy   = $request->get('sort_by', 'name');
        $sortDir  = $request->get('sort_dir', 'asc');

        // Validate sort options
        $allowedSorts = ['name', 'task_count', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            return response()->json(['message' => 'Invalid sort_by parameter'], 422);
        }

        // Base query: only non-admin users, eager load non-deleted tasks
        $query = User::where('is_admin', false)->with(['tasks' => function ($q) {
            $q->withoutTrashed();
        }]);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $users = $query->paginate($perPage);

        $mapped = $users->getCollection()->map(function ($user) {
            return [
                'id'              => $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'task_count'      => $user->tasks->count(),
                'completed_tasks' => $user->tasks->where('status', 'completed')->count(),
                'pending_tasks'   => $user->tasks->where('status', 'pending')->count(),
            ];
        });

        // Apply sorting
        $sorted = match ($sortBy) {
            'task_count' => $mapped->sortBy('task_count', SORT_REGULAR, $sortDir === 'desc'),
            'status'     => $mapped->sortBy('completed_tasks', SORT_REGULAR, $sortDir === 'desc'),
            default      => $mapped->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE, $sortDir === 'desc'),
        };

        return response()->json([
            'data'       => $sorted->values(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ],
        ]);
    }
}
