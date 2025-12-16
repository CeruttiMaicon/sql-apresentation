<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Criar estados e cidades primeiro
        $this->call(EstadoCidadeSeeder::class);

        // Exemplo: Criar 2 milhões de clientes
        // Ou com quantidade customizada:
        (new ClienteSeeder())->quantidade(2000000)->run();
    }
}
