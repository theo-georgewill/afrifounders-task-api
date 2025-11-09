<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); // specify guard if using Sanctum
        return $user;
    }

    #[Test]
    public function user_can_create_a_task()
    {
        $this->authenticate();

        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'Testing create endpoint',
            'status' => 'pending',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'New Task']);
    }

    #[Test]
    public function user_can_list_tasks()
    {
        $user = $this->authenticate();

        // Assign tasks to the authenticated user
        Task::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'current_page']);
    }

    #[Test]
    public function user_can_view_a_single_task()
    {
        $user = $this->authenticate();

        // Make sure task belongs to authenticated user
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $task->id]);
    }

    #[Test]
    public function user_can_update_a_task()
    {
        $user = $this->authenticate();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'completed',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'completed']);
    }

    #[Test]
    public function user_can_delete_a_task()
    {
        $user = $this->authenticate();

        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
