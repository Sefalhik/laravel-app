<?php

use App\Models\User;

it('shows default preferences', function () {
    $this->actingAs(User::factory()->create())
        ->get('/preferences')
        ->assertOk()
        ->assertSee('light')
        ->assertSee('fr');
});

it('shows preferences saved in session', function () {
    $this->actingAs(User::factory()->create())
        ->withSession(['theme' => 'dark', 'locale' => 'en'])
        ->get('/preferences')
        ->assertOk()
        ->assertSee('dark')
        ->assertSee('en');
});

it('stores valid preferences in session', function () {
    $this->actingAs(User::factory()->create())
        ->post('/preferences', ['theme' => 'dark', 'locale' => 'en'])
        ->assertRedirect(route('preferences.index'));

    $this->get('/preferences')
        ->assertSee('dark')
        ->assertSee('en');
});

it('rejects invalid theme', function () {
    $this->actingAs(User::factory()->create())
        ->post('/preferences', ['theme' => 'solarized', 'locale' => 'fr'])
        ->assertSessionHasErrors(['theme']);
});

it('rejects invalid locale', function () {
    $this->actingAs(User::factory()->create())
        ->post('/preferences', ['theme' => 'light', 'locale' => 'de'])
        ->assertSessionHasErrors(['locale']);
});
