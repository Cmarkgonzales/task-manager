<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for the task reordering logic.
 */
class TaskReorderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can reorder their own tasks.
     */
    public function test_user_can_reorder_their_tasks(): void
    {
        $user = User::factory()->create();

        $tasks = Task::factory()->count(3)->for($user)->sequence(
            ['order' => 0],
            ['order' => 1],
            ['order' => 2]
        )->create();

        $reversedIds = $tasks->pluck('id')->reverse()->values()->toArray();

        $this->actingAs($user)
             ->patchJson('/api/tasks/reorder', ['ordered_ids' => $reversedIds])
             ->assertOk();

        $ordered = Task::where('user_id', $user->id)->orderBy('order')->pluck('id')->toArray();

        $this->assertEquals($reversedIds, $ordered);
    }

    /**
     * Test that a user cannot reorder tasks they don't own.
     */
    public function test_user_cannot_reorder_others_tasks(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $task = Task::factory()->for($otherUser)->create(['order' => 0]);

        $this->actingAs($user)
             ->patchJson('/api/tasks/reorder', ['ordered_ids' => [$task->id]])
             ->assertForbidden();
    }
}
