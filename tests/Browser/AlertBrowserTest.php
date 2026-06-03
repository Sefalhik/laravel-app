<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

it('shows success alert after creating a post', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts/create')
            ->type('title', 'Article avec alerte')
            ->type('body', 'Contenu.')
            ->press('Publish')
            ->waitFor('#flash-alert')
            ->assertPresent('#flash-alert');
    });
});

it('dismisses alert when clicking the close button', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts/create')
            ->type('title', 'Article dismiss')
            ->type('body', 'Contenu.')
            ->press('Publish')
            ->waitFor('#flash-alert')
            ->click('#flash-alert button')
            ->waitUntil("getComputedStyle(document.getElementById('flash-alert')).display === 'none'");
    });
});

it('does not show alert after page reload', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts/create')
            ->type('title', 'Article reload')
            ->type('body', 'Contenu.')
            ->press('Publish')
            ->waitFor('#flash-alert')
            ->visit('/posts')
            ->assertNotPresent('#flash-alert');
    });
});

it('shows no alert on fresh page visit without prior action', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts')
            ->assertNotPresent('#flash-alert');
    });
});

it('shows success alert after saving preferences', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/preferences')
            ->select('theme', 'dark')
            ->select('locale', 'fr')
            ->click('main button[type="submit"]')
            ->waitFor('#flash-alert')
            ->assertPresent('#flash-alert');
    });
});

it('dismisses preferences alert', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/preferences')
            ->select('theme', 'light')
            ->select('locale', 'fr')
            ->click('main button[type="submit"]')
            ->waitFor('#flash-alert')
            ->click('#flash-alert button')
            ->waitUntil("getComputedStyle(document.getElementById('flash-alert')).display === 'none'");
    });
});

it('does not show preferences alert after reload', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/preferences')
            ->select('theme', 'dark')
            ->select('locale', 'fr')
            ->click('main button[type="submit"]')
            ->waitFor('#flash-alert')
            ->visit('/preferences')
            ->assertNotPresent('#flash-alert');
    });
});
