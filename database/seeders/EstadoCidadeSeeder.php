<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class EstadoCidadeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estadosCidades = $this->getEstadosCidades();

        $totalEstados = count($estadosCidades);
        $totalCidades = array_sum(array_map(fn($estado) => count($estado['cidades']), $estadosCidades));

        echo "Criando {$totalEstados} estados e {$totalCidades} cidades...\n\n";

        $progressEstados = new ProgressBar(new ConsoleOutput(), $totalEstados);
        $progressEstados->setFormat(' Estados: %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%');
        $progressEstados->start();

        $estadosCriados = 0;
        $estadosExistentes = 0;
        $cidadesCriadas = 0;
        $cidadesExistentes = 0;

        foreach ($estadosCidades as $estadoData) {
            // Usar firstOrCreate para evitar duplicatas (verifica por sigla que é única)
            $estado = Estado::firstOrCreate(
                ['sigla' => $estadoData['sigla']],
                [
                    'nome' => $estadoData['nome'],
                    'sigla' => $estadoData['sigla'],
                ]
            );

            if ($estado->wasRecentlyCreated) {
                $estadosCriados++;
            } else {
                $estadosExistentes++;
            }

            foreach ($estadoData['cidades'] as $cidadeNome) {
                // Usar firstOrCreate para evitar duplicatas (verifica por nome e id_estado)
                $cidade = Cidade::firstOrCreate(
                    [
                        'nome' => $cidadeNome,
                        'id_estado' => $estado->id,
                    ],
                    [
                        'nome' => $cidadeNome,
                        'id_estado' => $estado->id,
                    ]
                );

                if ($cidade->wasRecentlyCreated) {
                    $cidadesCriadas++;
                } else {
                    $cidadesExistentes++;
                }
            }

            $progressEstados->advance();
        }

        $progressEstados->finish();
        echo "\n\n";
        echo "✓ Estados: {$estadosCriados} criados, {$estadosExistentes} já existiam\n";
        echo "✓ Cidades: {$cidadesCriadas} criadas, {$cidadesExistentes} já existiam\n";
        echo "✓ Total: {$totalEstados} estados e {$totalCidades} cidades processados\n";
    }

    /**
     * Retorna array com estados e suas cidades.
     *
     * @return array
     */
    private function getEstadosCidades(): array
    {
        return [
            [
                'nome' => 'Acre',
                'sigla' => 'AC',
                'cidades' => ['Rio Branco', 'Cruzeiro do Sul', 'Sena Madureira', 'Tarauacá', 'Feijó'],
            ],
            [
                'nome' => 'Alagoas',
                'sigla' => 'AL',
                'cidades' => ['Maceió', 'Arapiraca', 'Palmeira dos Índios', 'Rio Largo', 'Penedo'],
            ],
            [
                'nome' => 'Amapá',
                'sigla' => 'AP',
                'cidades' => ['Macapá', 'Santana', 'Laranjal do Jari', 'Oiapoque', 'Mazagão'],
            ],
            [
                'nome' => 'Amazonas',
                'sigla' => 'AM',
                'cidades' => ['Manaus', 'Parintins', 'Itacoatiara', 'Manacapuru', 'Coari'],
            ],
            [
                'nome' => 'Bahia',
                'sigla' => 'BA',
                'cidades' => [
                    'Salvador', 'Feira de Santana', 'Vitória da Conquista', 'Camaçari', 'Juazeiro',
                    'Itabuna', 'Lauro de Freitas', 'Alagoinhas', 'Teixeira de Freitas', 'Barreiras',
                    'Porto Seguro', 'Simões Filho', 'Paulo Afonso', 'Eunápolis', 'Guanambi',
                ],
            ],
            [
                'nome' => 'Ceará',
                'sigla' => 'CE',
                'cidades' => ['Fortaleza', 'Caucaia', 'Juazeiro do Norte', 'Maracanaú', 'Sobral'],
            ],
            [
                'nome' => 'Distrito Federal',
                'sigla' => 'DF',
                'cidades' => ['Brasília', 'Ceilândia', 'Taguatinga', 'Samambaia', 'Planaltina'],
            ],
            [
                'nome' => 'Espírito Santo',
                'sigla' => 'ES',
                'cidades' => ['Vitória', 'Vila Velha', 'Cariacica', 'Serra', 'Cachoeiro de Itapemirim'],
            ],
            [
                'nome' => 'Goiás',
                'sigla' => 'GO',
                'cidades' => ['Goiânia', 'Aparecida de Goiânia', 'Anápolis', 'Rio Verde', 'Luziânia'],
            ],
            [
                'nome' => 'Maranhão',
                'sigla' => 'MA',
                'cidades' => ['São Luís', 'Imperatriz', 'Caxias', 'Timon', 'Codó'],
            ],
            [
                'nome' => 'Mato Grosso',
                'sigla' => 'MT',
                'cidades' => ['Cuiabá', 'Várzea Grande', 'Rondonópolis', 'Sinop', 'Tangará da Serra'],
            ],
            [
                'nome' => 'Mato Grosso do Sul',
                'sigla' => 'MS',
                'cidades' => ['Campo Grande', 'Dourados', 'Três Lagoas', 'Corumbá', 'Ponta Porã'],
            ],
            [
                'nome' => 'Minas Gerais',
                'sigla' => 'MG',
                'cidades' => [
                    'Belo Horizonte', 'Uberlândia', 'Contagem', 'Juiz de Fora', 'Betim',
                    'Montes Claros', 'Ribeirão das Neves', 'Uberaba', 'Governador Valadares', 'Ipatinga',
                    'Sete Lagoas', 'Divinópolis', 'Santa Luzia', 'Ibirité', 'Poços de Caldas',
                ],
            ],
            [
                'nome' => 'Pará',
                'sigla' => 'PA',
                'cidades' => ['Belém', 'Ananindeua', 'Santarém', 'Marabá', 'Paragominas'],
            ],
            [
                'nome' => 'Paraíba',
                'sigla' => 'PB',
                'cidades' => ['João Pessoa', 'Campina Grande', 'Santa Rita', 'Patos', 'Bayeux'],
            ],
            [
                'nome' => 'Paraná',
                'sigla' => 'PR',
                'cidades' => [
                    'Curitiba', 'Londrina', 'Maringá', 'Ponta Grossa', 'Cascavel',
                    'Foz do Iguaçu', 'Colombo', 'São José dos Pinhais', 'Paranaguá', 'Araucária',
                    'Guarapuava', 'Apucarana', 'Toledo', 'Pinhais', 'Campo Largo',
                ],
            ],
            [
                'nome' => 'Pernambuco',
                'sigla' => 'PE',
                'cidades' => ['Recife', 'Jaboatão dos Guararapes', 'Olinda', 'Caruaru', 'Petrolina'],
            ],
            [
                'nome' => 'Piauí',
                'sigla' => 'PI',
                'cidades' => ['Teresina', 'Parnaíba', 'Picos', 'Piripiri', 'Campo Maior'],
            ],
            [
                'nome' => 'Rio de Janeiro',
                'sigla' => 'RJ',
                'cidades' => [
                    'Rio de Janeiro', 'São Gonçalo', 'Duque de Caxias', 'Nova Iguaçu', 'Niterói',
                    'Campos dos Goytacazes', 'Petrópolis', 'Volta Redonda', 'Magé', 'Itaboraí',
                    'Cabo Frio', 'Angra dos Reis', 'Nova Friburgo', 'Barra Mansa', 'Teresópolis',
                ],
            ],
            [
                'nome' => 'Rio Grande do Norte',
                'sigla' => 'RN',
                'cidades' => ['Natal', 'Mossoró', 'Parnamirim', 'São Gonçalo do Amarante', 'Macaíba'],
            ],
            [
                'nome' => 'Rio Grande do Sul',
                'sigla' => 'RS',
                'cidades' => [
                    'Porto Alegre', 'Caxias do Sul', 'Pelotas', 'Canoas', 'Santa Maria',
                    'Gravataí', 'Viamão', 'Novo Hamburgo', 'São Leopoldo', 'Passo Fundo',
                    'Uruguaiana', 'Rio Grande', 'Cachoeirinha', 'Bagé', 'Sapucaia do Sul',
                ],
            ],
            [
                'nome' => 'Rondônia',
                'sigla' => 'RO',
                'cidades' => ['Porto Velho', 'Ji-Paraná', 'Ariquemes', 'Vilhena', 'Cacoal'],
            ],
            [
                'nome' => 'Roraima',
                'sigla' => 'RR',
                'cidades' => ['Boa Vista', 'Rorainópolis', 'Caracaraí', 'Alto Alegre', 'Bonfim'],
            ],
            [
                'nome' => 'Santa Catarina',
                'sigla' => 'SC',
                'cidades' => [
                    'Florianópolis', 'Joinville', 'Blumenau', 'São José', 'Criciúma',
                    'Chapecó', 'Itajaí', 'Lages', 'Jaraguá do Sul', 'Palhoça',
                    'Brusque', 'Balneário Camboriú', 'Tubarão', 'Caçador', 'Concórdia',
                ],
            ],
            [
                'nome' => 'São Paulo',
                'sigla' => 'SP',
                'cidades' => [
                    'São Paulo', 'Guarulhos', 'Campinas', 'São Bernardo do Campo', 'Santo André',
                    'Osasco', 'Ribeirão Preto', 'Sorocaba', 'Santos', 'Mauá',
                    'São José dos Campos', 'Diadema', 'Carapicuíba', 'Mogi das Cruzes', 'Piracicaba',
                    'Jundiaí', 'Bauru', 'Franca', 'Limeira', 'Praia Grande',
                ],
            ],
            [
                'nome' => 'Sergipe',
                'sigla' => 'SE',
                'cidades' => ['Aracaju', 'Nossa Senhora do Socorro', 'Lagarto', 'Itabaiana', 'São Cristóvão'],
            ],
            [
                'nome' => 'Tocantins',
                'sigla' => 'TO',
                'cidades' => ['Palmas', 'Araguaína', 'Gurupi', 'Porto Nacional', 'Paraíso do Tocantins'],
            ],
        ];
    }
}
