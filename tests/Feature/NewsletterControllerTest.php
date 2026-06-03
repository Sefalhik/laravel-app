<?php

use App\Jobs\SendNewsletterJob;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user',  'guard_name' => 'web']);
});

// --- index ---

it('allows admin to list newsletters', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    Newsletter::create(['subject' => 'Hello', 'body' => 'World']);

    $this->actingAs($admin)
        ->get('/newsletters')
        ->assertOk()
        ->assertSee('Hello');
});

it('denies non-admin access to newsletter list', function () {
    $this->actingAs(User::factory()->create())
        ->get('/newsletters')
        ->assertForbidden();
});

it('redirects guest from newsletter list', function () {
    $this->get('/newsletters')
        ->assertRedirect('/login');
});

// --- create ---

it('allows admin to access create form', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/newsletters/create')
        ->assertOk();
});

it('denies non-admin access to create form', function () {
    $this->actingAs(User::factory()->create())
        ->get('/newsletters/create')
        ->assertForbidden();
});

// --- store ---

it('allows admin to create and dispatch a newsletter', function () {
    Bus::fake();

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->post('/newsletters', ['subject' => 'Launch', 'body' => 'Content here'])
        ->assertRedirect(route('newsletters.index'));

    $this->assertDatabaseHas('newsletters', ['subject' => 'Launch']);

    Bus::assertDispatched(SendNewsletterJob::class, function ($job) {
        return $job->adminId === User::where('email', User::latest()->first()->email)->first()->id;
    });
});

it('dispatches job with correct newsletter id', function () {
    Bus::fake();

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->post('/newsletters', ['subject' => 'Test', 'body' => 'Body']);

    $newsletter = Newsletter::first();

    Bus::assertDispatched(SendNewsletterJob::class, fn($job) => $job->newsletterId === $newsletter->id);
});

it('denies non-admin from storing a newsletter', function () {
    $this->actingAs(User::factory()->create())
        ->post('/newsletters', ['subject' => 'Hack', 'body' => 'Nope'])
        ->assertForbidden();
});

it('validates subject is required on store', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->post('/newsletters', ['body' => 'Content'])
        ->assertSessionHasErrors(['subject']);
});

it('validates body is required on store', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->post('/newsletters', ['subject' => 'Hello'])
        ->assertSessionHasErrors(['body']);
});
