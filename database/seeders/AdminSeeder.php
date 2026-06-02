<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => 'password', // ⚠️ L13 : cast `hashed` sur User → NE PAS utiliser Hash::make() ici
            'role'     => 'admin',
        ]);
    }
}
