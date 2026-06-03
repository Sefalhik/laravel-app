<?php

use App\Models\Task;

it('lists tasks', function () {
    Task::factory()->count(5)->create();

    $this->getJson('/api/tasks')
        ->assertOk()
        ->assertJsonCount(5, 'data');
});

it('creates a task', function () {
    $this->postJson('/api/tasks', ['title' => 'New task'])
        ->assertCreated()
        ->assertJsonPath('data.title', 'New task');

    $this->assertDatabaseHas('tasks', ['title' => 'New task']);
});

it('validates title on store', function () {
    $this->postJson('/api/tasks', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

it('shows a task', function () {
    $task = Task::factory()->create();

    $this->getJson("/api/tasks/{$task->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $task->id);
});

it('returns 404 for missing task', function () {
    $this->getJson('/api/tasks/9999')
        ->assertNotFound();
});

it('updates a task status', function () {
    $task = Task::factory()->create(['status' => 'todo']);

    $this->putJson("/api/tasks/{$task->id}", ['status' => 'done'])
        ->assertOk()
        ->assertJsonPath('data.status', 'done');

    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'done']);
});

it('updates a task title', function () {
    $task = Task::factory()->create(['title' => 'Old title']);

    $this->putJson("/api/tasks/{$task->id}", ['title' => 'New title'])
        ->assertOk()
        ->assertJsonPath('data.title', 'New title');
});

it('updates a task due date', function () {
    $task = Task::factory()->create(['due_date' => null]);
    $future = now()->addDays(10)->toDateString();

    $this->putJson("/api/tasks/{$task->id}", ['due_date' => $future])
        ->assertOk()
        ->assertJsonPath('data.due_date', fn($value) => str_starts_with($value, $future));
});

it('does not require title on update', function () {
    $task = Task::factory()->create(['title' => 'Keep me']);

    $this->putJson("/api/tasks/{$task->id}", ['status' => 'in_progress'])
        ->assertOk();

    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Keep me']);
});

it('rejects empty title on update when provided', function () {
    $task = Task::factory()->create();

    $this->putJson("/api/tasks/{$task->id}", ['title' => ''])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

it('rejects invalid status on update', function () {
    $task = Task::factory()->create();

    $this->putJson("/api/tasks/{$task->id}", ['status' => 'invalid'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

it('rejects past due date on update', function () {
    $task = Task::factory()->create();

    $this->putJson("/api/tasks/{$task->id}", ['due_date' => now()->subDays(1)->toDateString()])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['due_date']);
});

it('returns 404 when updating missing task', function () {
    $this->putJson('/api/tasks/9999', ['status' => 'done'])
        ->assertNotFound();
});

it('deletes a task', function () {
    $task = Task::factory()->create();

    $this->deleteJson("/api/tasks/{$task->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('filters tasks by status', function () {
    Task::factory()->count(3)->create(['status' => 'todo']);
    Task::factory()->count(2)->create(['status' => 'done']);

    $this->getJson('/api/tasks?status=todo')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('rejects invalid status on store', function () {
    $this->postJson('/api/tasks', ['title' => 'A task', 'status' => 'invalid'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

it('rejects past due date on store', function () {
    $this->postJson('/api/tasks', ['title' => 'A task', 'due_date' => now()->subDays(1)->toDateString()])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['due_date']);
});

it('returns 404 when deleting missing task', function () {
    $this->deleteJson('/api/tasks/9999')
        ->assertNotFound();
});

it('returns expected resource shape', function () {
    $task = Task::factory()->create();

    $this->getJson("/api/tasks/{$task->id}")
        ->assertOk()
        ->assertJsonStructure([
            'data' => ['id', 'title', 'description', 'status', 'due_date', 'created_at', 'updated_at'],
        ]);
});

it('paginates tasks', function () {
    Task::factory()->count(15)->create();

    $this->getJson('/api/tasks')
        ->assertOk()
        ->assertJsonPath('meta.total', 15)
        ->assertJsonCount(10, 'data');
});

it('returns overdue tasks scope via filter', function () {
    Task::factory()->create(['status' => 'todo', 'due_date' => now()->subDays(3)]);
    Task::factory()->create(['status' => 'done', 'due_date' => now()->subDays(1)]);
    Task::factory()->create(['status' => 'todo', 'due_date' => now()->addDays(5)]);

    $overdue = Task::overdue()->get();

    expect($overdue)->toHaveCount(1)
        ->and($overdue->first()->status)->toBe('todo');
});
