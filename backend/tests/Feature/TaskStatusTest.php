<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test the task status update endpoint, including toggle and explicit set.
 */
class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test User can toggle the task's completion status. */
    public function test_user_can_toggle_task_status()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['status' => 'pending']);

        $this->actingAs($user)
            ->patchJson("/api/tasks/{$task->id}/status")
            ->assertOk()
            ->assertJsonFragment(['status' => 'completed']);
    }

    /** @test User can explicitly set the task status. */
    public function test_user_can_set_status_explicitly()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create(['status' => 'pending']);

        $this->actingAs($user)
            ->patchJson("/api/tasks/{$task->id}/status", ['status' => 'completed'])
            ->assertOk()
            ->assertJsonFragment(['status' => 'completed']);
    }
}
