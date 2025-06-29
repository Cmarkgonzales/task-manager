<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for task CRUD operations:
 * - Create
 * - Update
 * - Delete (admin-only)
 */
class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;

    /**
     * Set up a regular user and an admin for all tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['is_admin' => false]);
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    /**
     * Test that an authenticated user can create a task.
     */
    public function test_user_can_create_task(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'Testing create',
            'priority' => 'high',
        ]);

        $response->assertCreated()
                 ->assertJsonFragment(['title' => 'New Task']);
    }

    /**
     * Test that the task owner can update their task.
     */
    public function test_user_can_update_task(): void
    {
        $task = Task::factory()->for($this->user)->create([
            'title' => 'Old',
            'description' => 'Initial',
            'priority' => 'low',
        ]);

        $this->actingAs($this->user)
             ->putJson("/api/tasks/{$task->id}", [
                 'title' => 'Updated',
                 'description' => $task->description,
                 'priority' => $task->priority,
             ])
             ->assertOk()
             ->assertJsonFragment(['title' => 'Updated']);
    }

    /**
     * Test that only an admin can delete a task (soft delete).
     */
    public function test_admin_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $this->actingAs($this->admin)
             ->deleteJson("/api/tasks/{$task->id}")
             ->assertOk();

        $this->assertSoftDeleted($task);
    }

    /**
     * Test that a regular (non-admin) user cannot delete tasks.
     */
    public function test_non_admin_cannot_delete_task(): void
    {
        $task = Task::factory()->create();

        $this->actingAs($this->user)
             ->deleteJson("/api/tasks/{$task->id}")
             ->assertForbidden();
    }
}
