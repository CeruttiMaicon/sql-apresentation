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
        // Tipos de logradouros comuns no Brasil
        $tiposLogradouro = [
            'Rua', 'Avenida', 'Travessa', 'Alameda', 'Praça',
            'Estrada', 'Rodovia', 'Viela', 'Beco', 'Largo',
            'Vila', 'Jardim', 'Parque', 'Chácara', 'Sítio',
        ];

        // Prefixos e sufixos para nomes de ruas brasileiras
        $nomesRua = [
            fake('pt_BR')->lastName(),
            fake('pt_BR')->firstName() . ' ' . fake('pt_BR')->lastName(),
            fake('pt_BR')->randomElement(['São', 'Santa', 'Nossa Senhora']) . ' ' . fake('pt_BR')->firstName(),
            fake('pt_BR')->randomElement(['Dom', 'Dona']) . ' ' . fake('pt_BR')->firstName(),
        ];

        // Tipos de bairros comuns no Brasil
        $prefixosBairro = [
            'Centro', 'Vila', 'Jardim', 'Parque', 'Bairro',
            'Distrito', 'Setor', 'Zona', 'Alto', 'Baixo',
            'Nova', 'São', 'Santa', 'Morro', 'Loteamento',
        ];

        $nomesBairro = [
            fake('pt_BR')->firstName(),
            fake('pt_BR')->lastName(),
            fake('pt_BR')->randomElement(['São', 'Santa']) . ' ' . fake('pt_BR')->firstName(),
            fake('pt_BR')->randomElement(['Nova', 'Vila']) . ' ' . fake('pt_BR')->firstName(),
        ];

        $tipoLogradouro = fake('pt_BR')->randomElement($tiposLogradouro);
        $nomeRua = fake('pt_BR')->randomElement($nomesRua);
        $prefixoBairro = fake('pt_BR')->randomElement($prefixosBairro);
        $nomeBairro = fake('pt_BR')->randomElement($nomesBairro);

        return [
            'cidade_id' => \App\Models\Cidade::factory(),
            'estado_id' => \App\Models\Estado::factory(),
            'rua' => $tipoLogradouro . ' ' . $nomeRua,
            'cep' => fake('pt_BR')->numerify('#####-###'),
            'numero' => fake('pt_BR')->numberBetween(1, 9999),
            'bairro' => $prefixoBairro . ' ' . $nomeBairro,
        ];
    }
}
