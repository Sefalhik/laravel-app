<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

it('redirects guest to login when accessing dashboard', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/dashboard')
            ->assertPathIs('/login');
    });
});

it('redirects guest to login when accessing protected routes', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/posts/create')->assertPathIs('/login')
            ->visit('/preferences')->assertPathIs('/login');
    });
});

it('shows validation errors on empty login form', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login');
        $browser->script("document.querySelector('form').noValidate = true");
        $browser->press('Sign in')
            ->assertPathIs('/login')
            ->assertPresent('.text-red-600');
    });
});

it('shows error on invalid credentials', function () {
    User::factory()->create(['email' => 'user@example.com', 'password' => 'password']);

    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->type('email', 'user@example.com')
            ->type('password', 'wrong-password')
            ->press('Sign in')
            ->waitFor('.text-red-600')
            ->assertPresent('.text-red-600');
    });
});

it('shows error for unknown email', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->type('email', 'nobody@example.com')
            ->type('password', 'password')
            ->press('Sign in')
            ->waitFor('.text-red-600')
            ->assertPresent('.text-red-600');
    });
});

it('logs in with valid credentials and reaches dashboard', function () {
    User::factory()->create(['email' => 'user@example.com', 'password' => 'password']);

    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
            ->type('email', 'user@example.com')
            ->type('password', 'password')
            ->press('Sign in')
            ->waitUntil("window.location.pathname === '/dashboard'")
            ->assertPathIs('/dashboard');
    });
});

it('logs out and redirects to home', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/dashboard')
            ->press('Logout')
            ->assertPathIs('/');
    });
});

it('cannot access dashboard after logout', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/dashboard')
            ->press('Logout')
            ->visit('/dashboard')
            ->assertPathIs('/login');
    });
});

it('registers a new account and reaches dashboard', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/register')
            ->type('name', 'John Doe')
            ->type('email', 'john@example.com')
            ->type('password', 'password')
            ->type('password_confirmation', 'password')
            ->press('Sign up')
            ->assertPathIs('/dashboard');
    });
});

it('shows error when passwords do not match on register', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/register');
        $browser->script("document.querySelector('form').noValidate = true");
        $browser->type('name', 'John Doe')
            ->type('email', 'john@example.com')
            ->type('password', 'password')
            ->type('password_confirmation', 'different')
            ->press('Sign up')
            ->assertPathIs('/register')
            ->assertPresent('.text-red-600');
    });
});

it('shows error when email is already taken on register', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->browse(function (Browser $browser) {
        $browser->visit('/register')
            ->type('name', 'Jane Doe')
            ->type('email', 'taken@example.com')
            ->type('password', 'password')
            ->type('password_confirmation', 'password')
            ->press('Sign up')
            ->assertPathIs('/register')
            ->assertPresent('.text-red-600');
    });
});
