<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test the stats endpoint to ensure accurate task counts are returned.
 */
class TaskStatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test Returns correct stats for total, completed, and pending tasks. */
    public function test_user_can_get_task_statistics()
    {
        $user = User::factory()->create();

        // Ensure correct order and separation
        Task::factory()->count(3)->for($user)->create(['status' => 'completed']);
        Task::factory()->count(2)->for($user)->create(['status' => 'pending']);

        $response = $this->actingAs($user)->getJson('/api/tasks/stats');

        $response->assertOk()
                ->assertJson([
                    'total' => 5,
                    'completed' => 3,
                    'pending' => 2,
                ]);
    }
}
