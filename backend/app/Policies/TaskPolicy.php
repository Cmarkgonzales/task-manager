<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any tasks.
     * Only admins may want to use this for admin-wide task listing.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can view a specific task.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return true; // all authenticated users can create tasks
    }

    /**
     * Determine whether the user can update the task.
     * For example, updating title, description, status, etc.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the task.
     * Only admins are allowed to delete tasks.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can restore the task.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the task.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->is_admin;
    }
}
