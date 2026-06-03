<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

it('displays stats', function () {
    User::factory()->count(2)->create();
    Task::factory()->count(3)->create();

    $this->get('/stats')
        ->assertOk()
        ->assertSee('2')
        ->assertSee('3');
});

it('serves stats from cache on second call', function () {
    User::factory()->count(2)->create();

    $this->get('/stats');

    User::factory()->count(5)->create();

    $this->get('/stats')
        ->assertOk()
        ->assertSee('2');
});

it('allows admin to flush cache', function () {
    Cache::put('stats', ['users' => 99, 'tasks' => 99], 3600);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->post('/cache/flush')
        ->assertRedirect(route('dashboard'));

    expect(Cache::has('stats'))->toBeFalse();
});

it('denies non-admin from flushing cache', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/cache/flush')
        ->assertForbidden();
});
