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
            'nome' => fake('pt_BR')->name(),
            'email' => fake('pt_BR')->unique()->safeEmail(),
            'sexo' => fake('pt_BR')->randomElement(['M', 'F']),
            'celular' => fake('pt_BR')->numerify('(##) #####-####'),
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
            $cidade = Cidade::where('estado_id', $estado->id)->inRandomOrder()->first();

            // Se não houver cidades para esse estado, criar uma (fallback)
            if (!$cidade) {
                $cidade = Cidade::factory()->create([
                    'estado_id' => $estado->id,
                ]);
            }

            // Criar Endereco relacionado ao Cliente
            $endereco = Endereco::factory()->create([
                'cidade_id' => $cidade->id,
                'estado_id' => $estado->id,
            ]);

            // Associar o endereço ao cliente
            $cliente->update(['endereco_id' => $endereco->id]);
        });
    }
}
