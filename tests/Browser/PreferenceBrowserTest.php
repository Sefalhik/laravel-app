<?php

use App\Models\User;
use Laravel\Dusk\Browser;

it('defaults to light theme', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/dashboard')
            ->waitUntil("document.documentElement.getAttribute('data-theme') === 'light'");
    });
});

it('switches to dark theme and persists after reload', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/preferences')
            ->select('theme', 'dark')
            ->select('locale', 'fr')
            ->click('main button[type="submit"]')
            ->waitUntil("window.location.pathname === '/preferences'")
            ->visit('/dashboard')
            ->waitUntil("document.documentElement.getAttribute('data-theme') === 'dark'");
    });
});

it('switches back to light theme', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/preferences')
            ->select('theme', 'dark')
            ->select('locale', 'fr')
            ->click('main button[type="submit"]')
            ->waitUntil("window.location.pathname === '/preferences'")
            ->select('theme', 'light')
            ->click('main button[type="submit"]')
            ->waitUntil("window.location.pathname === '/preferences'")
            ->visit('/dashboard')
            ->waitUntil("document.documentElement.getAttribute('data-theme') === 'light'");
    });
});

it('switches locale to english and html lang reflects it', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/preferences')
            ->select('locale', 'en')
            ->select('theme', 'light')
            ->click('main button[type="submit"]')
            ->waitUntil("window.location.pathname === '/preferences'")
            ->visit('/stats')
            ->waitUntil("document.documentElement.lang === 'en'");
    });
});

it('switches locale back to french', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/preferences')
            ->select('locale', 'fr')
            ->select('theme', 'light')
            ->click('main button[type="submit"]')
            ->waitUntil("window.location.pathname === '/preferences'")
            ->visit('/stats')
            ->waitUntil("document.documentElement.lang === 'fr'");
    });
});

it('rejects invalid theme value injected outside select options', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)->visit('/preferences');
        $browser->script("document.querySelector('select[name=\"theme\"]').value = 'hacker'");
        $browser->click('main button[type="submit"]')
            ->waitFor('.text-red-600')
            ->assertPresent('.text-red-600');
    });
});

it('rejects invalid locale value injected outside select options', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)->visit('/preferences');
        $browser->script("document.querySelector('select[name=\"locale\"]').value = 'zh'");
        $browser->click('main button[type="submit"]')
            ->waitFor('.text-red-600')
            ->assertPresent('.text-red-600');
    });
});

it('does not leak theme between different user sessions', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $this->browse(function (Browser $browserA, Browser $browserB) use ($userA, $userB) {
        $browserA->loginAs($userA)
            ->visit('/preferences')
            ->select('theme', 'dark')
            ->select('locale', 'fr')
            ->click('main button[type="submit"]')
            ->waitUntil("window.location.pathname === '/preferences'");

        $browserB->loginAs($userB)
            ->visit('/dashboard')
            ->waitUntil("document.documentElement.getAttribute('data-theme') === 'light'");
    });
});
