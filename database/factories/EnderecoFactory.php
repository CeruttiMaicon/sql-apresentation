<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Endereco>
 */
class EnderecoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => \App\Models\Cliente::factory(),
            'id_cidade' => \App\Models\Cidade::factory(),
            'id_estado' => \App\Models\Estado::factory(),
            'rua' => fake()->streetName(),
            'cep' => fake()->numerify('#####-###'),
            'numero' => fake()->buildingNumber(),
            'bairro' => fake()->citySuffix(),
        ];
    }
}
