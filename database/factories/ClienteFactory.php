<?php

namespace Database\Factories;

use App\Models\Cidade;
use App\Models\Endereco;
use App\Models\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'sexo' => fake()->randomElement(['M', 'F']),
            'celular' => fake()->numerify('(##) #####-####'),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function ($cliente) {
            // Buscar um estado existente aleatório
            $estado = Estado::inRandomOrder()->first();

            // Se não houver estados, criar um (fallback)
            if (!$estado) {
                $estado = Estado::factory()->create();
            }

            // Buscar uma cidade do estado selecionado
            $cidade = Cidade::where('id_estado', $estado->id)->inRandomOrder()->first();

            // Se não houver cidades para esse estado, criar uma (fallback)
            if (!$cidade) {
                $cidade = Cidade::factory()->create([
                    'id_estado' => $estado->id,
                ]);
            }

            // Criar Endereco relacionado ao Cliente
            Endereco::factory()->create([
                'cliente_id' => $cliente->id,
                'id_cidade' => $cidade->id,
                'id_estado' => $estado->id,
            ]);
        });
    }
}
