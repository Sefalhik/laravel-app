<?php

use App\Models\Post;
use App\Models\User;
use Laravel\Dusk\Browser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user',  'guard_name' => 'web']);
});

it('shows the post list', function () {
    $user = User::factory()->create();
    Post::factory()->create(['title' => 'Mon article', 'user_id' => $user->id]);

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts')
            ->assertSee('Mon article');
    });
});

it('creates a post and sees it in the list', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts/create')
            ->type('title', 'Nouvel article E2E')
            ->type('body', 'Contenu du test E2E.')
            ->press('Publish')
            ->waitUntil("window.location.pathname === '/posts'")
            ->assertSee('Nouvel article E2E');
    });
});

it('shows validation errors on empty post form', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)->visit('/posts/create');
        $browser->script("document.querySelector('main form').noValidate = true");
        $browser->press('Publish')
            ->waitFor('.text-red-600')
            ->assertPresent('.text-red-600');
    });
});

it('shows validation error when title exceeds 255 characters', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)->visit('/posts/create');
        $browser->script("document.querySelector('main form').noValidate = true");
        $browser->type('title', str_repeat('a', 256))
            ->type('body', 'Contenu valide.')
            ->press('Publish')
            ->waitFor('.text-red-600')
            ->assertPresent('.text-red-600');
    });
});

it('returns 404 for a non-existent post', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts/99999')
            ->assertSee('404');
    });
});

it('allows author to edit their post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['title' => 'Avant', 'user_id' => $user->id]);

    $this->browse(function (Browser $browser) use ($user, $post) {
        $browser->loginAs($user)
            ->visit("/posts/{$post->id}/edit")
            ->value('input[name="title"]', 'Après modification')
            ->press('Update')
            ->assertPathIs('/posts')
            ->assertSee('Après modification');
    });
});

it('shows validation error when title is cleared on edit', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $this->browse(function (Browser $browser) use ($user, $post) {
        $browser->loginAs($user)->visit("/posts/{$post->id}/edit");
        $browser->script("document.querySelector('main form').noValidate = true");
        $browser->value('input[name="title"]', '')
            ->press('Update')
            ->waitFor('.text-red-600')
            ->assertPresent('.text-red-600');
    });
});

it('denies non-author from accessing edit form', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $post  = Post::factory()->create(['user_id' => $owner->id]);

    $this->browse(function (Browser $browser) use ($other, $post) {
        $browser->loginAs($other)
            ->visit("/posts/{$post->id}/edit")
            ->assertSee('403');
    });
});

it('returns 404 when editing a non-existent post', function () {
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user)
            ->visit('/posts/99999/edit')
            ->assertSee('404');
    });
});

it('allows author to delete their post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['title' => 'À supprimer', 'user_id' => $user->id]);

    $this->browse(function (Browser $browser) use ($user, $post) {
        $browser->loginAs($user)
            ->visit('/posts')
            ->click("form[action\$='/posts/{$post->id}'] button[type='submit']")
            ->assertPathIs('/posts')
            ->assertDontSee('À supprimer');
    });
});

it('allows admin to delete any post', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $post = Post::factory()->create(['title' => "Post d'un autre", 'user_id' => $owner->id]);

    $this->browse(function (Browser $browser) use ($admin, $post) {
        $browser->loginAs($admin)
            ->visit('/posts')
            ->click("form[action\$='/posts/{$post->id}'] button[type='submit']")
            ->assertPathIs('/posts')
            ->assertDontSee("Post d'un autre");
    });
});
