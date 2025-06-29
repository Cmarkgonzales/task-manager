<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test that unauthorized users cannot access tasks they don't own.
 */
class TaskAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test User cannot view tasks that belong to another user. */
    public function test_user_cannot_view_others_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->getJson("/api/tasks/{$task->id}")
            ->assertStatus(403);
    }
}
