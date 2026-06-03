<?php

it('sets locale from session', function () {
    $this->withSession(['locale' => 'en'])
        ->get('/stats')
        ->assertOk()
        ->assertSee('lang="en"', false);
});

it('falls back to default app locale when no session', function () {
    $this->get('/stats')
        ->assertOk()
        ->assertSee('lang="' . config('app.locale') . '"', false);
});
