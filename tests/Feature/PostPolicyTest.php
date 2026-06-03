<?php

use App\Models\Post;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user',  'guard_name' => 'web']);
});

// --- store ---

it('allows any authenticated user to create a post', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/posts', ['title' => 'Hello', 'body' => 'World'])
        ->assertRedirect(route('posts.index'));

    $this->assertDatabaseHas('posts', ['title' => 'Hello']);
});

// --- edit ---

it('allows author to access edit form', function () {
    $author = User::factory()->create();
    $post   = Post::factory()->create(['user_id' => $author->id]);

    $this->actingAs($author)
        ->get("/posts/{$post->id}/edit")
        ->assertOk();
});

it('denies other user access to edit form', function () {
    $post  = Post::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($other)
        ->get("/posts/{$post->id}/edit")
        ->assertForbidden();
});

it('allows admin to access any edit form', function () {
    $post  = Post::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get("/posts/{$post->id}/edit")
        ->assertOk();
});

// --- update ---

it('allows author to update their post', function () {
    $author = User::factory()->create();
    $post   = Post::factory()->create(['user_id' => $author->id]);

    $this->actingAs($author)
        ->put("/posts/{$post->id}", ['title' => 'Updated', 'body' => 'Content'])
        ->assertRedirect(route('posts.index'));

    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated']);
});

it('denies other user from updating a post', function () {
    $post  = Post::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($other)
        ->put("/posts/{$post->id}", ['title' => 'Hacked', 'body' => 'Content'])
        ->assertForbidden();
});

it('allows admin to update any post', function () {
    $post  = Post::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->put("/posts/{$post->id}", ['title' => 'Admin edit', 'body' => 'Content'])
        ->assertRedirect(route('posts.index'));
});

// --- destroy ---

it('allows author to delete their post', function () {
    $author = User::factory()->create();
    $post   = Post::factory()->create(['user_id' => $author->id]);

    $this->actingAs($author)
        ->delete("/posts/{$post->id}")
        ->assertRedirect(route('posts.index'));

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('denies other user from deleting a post', function () {
    $post  = Post::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($other)
        ->delete("/posts/{$post->id}")
        ->assertForbidden();
});

it('allows admin to delete any post', function () {
    $post  = Post::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->delete("/posts/{$post->id}")
        ->assertRedirect(route('posts.index'));

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});
