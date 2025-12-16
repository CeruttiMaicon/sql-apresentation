<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estado>
 */
class EstadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $estados = [
            ['nome' => 'Acre', 'sigla' => 'AC'],
            ['nome' => 'Alagoas', 'sigla' => 'AL'],
            ['nome' => 'Amapá', 'sigla' => 'AP'],
            ['nome' => 'Amazonas', 'sigla' => 'AM'],
            ['nome' => 'Bahia', 'sigla' => 'BA'],
            ['nome' => 'Ceará', 'sigla' => 'CE'],
            ['nome' => 'Distrito Federal', 'sigla' => 'DF'],
            ['nome' => 'Espírito Santo', 'sigla' => 'ES'],
            ['nome' => 'Goiás', 'sigla' => 'GO'],
            ['nome' => 'Maranhão', 'sigla' => 'MA'],
            ['nome' => 'Mato Grosso', 'sigla' => 'MT'],
            ['nome' => 'Mato Grosso do Sul', 'sigla' => 'MS'],
            ['nome' => 'Minas Gerais', 'sigla' => 'MG'],
            ['nome' => 'Pará', 'sigla' => 'PA'],
            ['nome' => 'Paraíba', 'sigla' => 'PB'],
            ['nome' => 'Paraná', 'sigla' => 'PR'],
            ['nome' => 'Pernambuco', 'sigla' => 'PE'],
            ['nome' => 'Piauí', 'sigla' => 'PI'],
            ['nome' => 'Rio de Janeiro', 'sigla' => 'RJ'],
            ['nome' => 'Rio Grande do Norte', 'sigla' => 'RN'],
            ['nome' => 'Rio Grande do Sul', 'sigla' => 'RS'],
            ['nome' => 'Rondônia', 'sigla' => 'RO'],
            ['nome' => 'Roraima', 'sigla' => 'RR'],
            ['nome' => 'Santa Catarina', 'sigla' => 'SC'],
            ['nome' => 'São Paulo', 'sigla' => 'SP'],
            ['nome' => 'Sergipe', 'sigla' => 'SE'],
            ['nome' => 'Tocantins', 'sigla' => 'TO'],
        ];

        $estado = fake()->randomElement($estados);

        return [
            'nome' => $estado['nome'],
            'sigla' => $estado['sigla'],
        ];
    }
}
