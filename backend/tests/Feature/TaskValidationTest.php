<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test input validation rules for task creation.
 */
class TaskValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test Validate required fields on task creation. */
    public function test_task_creation_requires_title_and_priority()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/tasks', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'priority']);
    }

    /** @test Validate that invalid priority values are rejected. */
    public function test_invalid_priority_is_rejected()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/tasks', [
                'title' => 'Test',
                'priority' => 'urgent',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['priority']);
    }
}
