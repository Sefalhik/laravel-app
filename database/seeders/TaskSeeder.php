<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'title'       => 'Configurer l\'environnement de développement',
            'description' => 'Installer PHP, Composer, Laravel et les dépendances du projet.',
            'status'      => 'done',
        ]);

        Task::create([
            'title'       => 'Implémenter l\'authentification',
            'description' => 'Mettre en place JWT et les routes de login/logout.',
            'status'      => 'in_progress',
        ]);

        Task::create([
            'title'       => 'Rédiger la documentation API',
            'description' => null,
            'status'      => 'todo',
            'due_date'    => now()->addDays(14),
        ]);
    }
}
