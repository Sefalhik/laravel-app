<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /** @codeCoverageIgnore — stub artisan non utilisé, l'index est public */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /** @codeCoverageIgnore — stub artisan non utilisé, le show est public */
    public function view(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    /** @codeCoverageIgnore — stub artisan non utilisé, pas de soft delete sur Post */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /** @codeCoverageIgnore — stub artisan non utilisé, pas de soft delete sur Post */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
