<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cidade>
 */
class CidadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cidades = [
            'São Paulo', 'Rio de Janeiro', 'Brasília', 'Salvador', 'Fortaleza',
            'Belo Horizonte', 'Manaus', 'Curitiba', 'Recife', 'Goiânia',
            'Porto Alegre', 'Belém', 'Guarulhos', 'Campinas', 'São Luís',
            'São Gonçalo', 'Maceió', 'Duque de Caxias', 'Natal', 'Teresina',
            'Campo Grande', 'Nova Iguaçu', 'São Bernardo do Campo', 'João Pessoa', 'Santo André',
            'Osasco', 'Jaboatão dos Guararapes', 'Ribeirão Preto', 'Uberlândia', 'Contagem',
            'Aracaju', 'Feira de Santana', 'Cuiabá', 'Joinville', 'Aparecida de Goiânia',
            'Londrina', 'Ananindeua', 'Porto Velho', 'Serra', 'Niterói',
            'Caxias do Sul', 'Campos dos Goytacazes', 'Macapá', 'Vila Velha', 'Florianópolis',
        ];

        return [
            'nome' => fake()->randomElement($cidades),
        ];
    }
}
