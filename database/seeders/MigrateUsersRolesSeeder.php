<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MigrateUsersRolesSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->each(function (User $user) {
            $role = $user->role ?? 'user';

            if (! $user->hasRole($role)) {
                $user->assignRole($role);
            }
        });
    }
}
