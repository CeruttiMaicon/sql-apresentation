<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class ClienteSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Quantidade de clientes a serem criados.
     * Altere este valor para criar mais ou menos clientes.
     */
    private int $quantidade = 10;

    /**
     * Tamanho do lote para criação em batches (melhor performance).
     */
    private int $tamanhoLote = 5000;

    /**
     * Cache de estados e cidades para evitar queries repetidas
     */
    private array $estados = [];
    private array $cidadesPorEstado = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inicio = microtime(true);
        
        // Carregar estados e cidades em memória uma única vez
        $this->carregarEstadosECidades();
        
        $totalValor = $this->quantidade > 0 ? $this->quantidade : 1;
        
        $progress = new ProgressBar(new ConsoleOutput(), $totalValor);
        $progress->setFormat($this->formatProgress());
        $progress->start();

        $criados = 0;
        $lotes = ceil($this->quantidade / $this->tamanhoLote);

        for ($lote = 0; $lote < $lotes; $lote++) {
            $restante = $this->quantidade - $criados;
            $tamanhoAtual = min($this->tamanhoLote, $restante);

            // Criar lote usando inserção em massa
            $this->criarLoteClientes($tamanhoAtual);

            $criados += $tamanhoAtual;

            // Atualizar barra de progresso
            $progress->setProgress($criados);
        }

        $progress->finish();
        echo "\n\n";

        $tempoTotal = microtime(true) - $inicio;
        $velocidadeMedia = $this->quantidade / $tempoTotal;

        echo "✓ {$this->quantidade} clientes criados com sucesso!\n";
        echo "  Tempo total: " . gmdate('H:i:s', (int)$tempoTotal) . "\n";
        echo "  Velocidade média: " . number_format($velocidadeMedia, 0) . " clientes/segundo\n";
    }

    /**
     * Carrega estados e cidades em memória para evitar queries repetidas.
     */
    private function carregarEstadosECidades(): void
    {
        // Carregar todos os estados
        $this->estados = Estado::all()->toArray();
        
        // Carregar todas as cidades agrupadas por estado
        $cidades = Cidade::all();
        foreach ($cidades as $cidade) {
            $this->cidadesPorEstado[$cidade->estado_id][] = $cidade->id;
        }
    }

    /**
     * Cria um lote de clientes usando inserção em massa.
     */
    private function criarLoteClientes(int $quantidade): void
    {
        $clientes = [];
        $enderecos = [];
        $now = now();

        for ($i = 0; $i < $quantidade; $i++) {
            // Selecionar estado e cidade aleatórios do cache
            $estado = $this->estados[array_rand($this->estados)];
            $cidadesDisponiveis = $this->cidadesPorEstado[$estado['id']] ?? [];
            
            if (empty($cidadesDisponiveis)) {
                continue; // Pular se não houver cidades para o estado
            }
            
            $cidadeId = $cidadesDisponiveis[array_rand($cidadesDisponiveis)];

            // Gerar dados do cliente (usar timestamp para garantir email único)
            $timestamp = time() . '_' . $i . '_' . uniqid();
            $dominios = ['gmail.com', 'hotmail.com', 'yahoo.com.br', 'outlook.com', 'uol.com.br'];
            $clientes[] = [
                'nome' => fake('pt_BR')->name(),
                'email' => 'cliente_' . $timestamp . '@' . fake('pt_BR')->randomElement($dominios),
                'sexo' => fake('pt_BR')->randomElement(['M', 'F']),
                'celular' => fake('pt_BR')->numerify('(##) #####-####'),
                'endereco_id' => null, // Será atualizado depois
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Gerar dados do endereço
            $enderecos[] = [
                'cidade_id' => $cidadeId,
                'estado_id' => $estado['id'],
                'rua' => $this->gerarRua(),
                'cep' => fake('pt_BR')->numerify('#####-###'),
                'numero' => fake('pt_BR')->numberBetween(1, 9999),
                'bairro' => $this->gerarBairro(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Obter o último ID antes de inserir
        $ultimoIdEndereco = DB::table('enderecos')->max('id') ?? 0;
        
        // Inserir endereços em lote
        DB::table('enderecos')->insert($enderecos);
        
        // Buscar os IDs dos endereços recém-criados (últimos N inseridos)
        $enderecosIds = DB::table('enderecos')
            ->where('id', '>', $ultimoIdEndereco)
            ->orderBy('id', 'asc')
            ->pluck('id')
            ->toArray();

        // Associar endereços aos clientes
        foreach ($clientes as $index => &$cliente) {
            $cliente['endereco_id'] = $enderecosIds[$index] ?? null;
        }

        // Inserir clientes em lote
        DB::table('clientes')->insert($clientes);
    }

    /**
     * Gera um nome de rua em português brasileiro.
     */
    private function gerarRua(): string
    {
        $tiposLogradouro = [
            'Rua', 'Avenida', 'Travessa', 'Alameda', 'Praça',
            'Estrada', 'Rodovia', 'Viela', 'Beco', 'Largo',
            'Vila', 'Jardim', 'Parque', 'Chácara', 'Sítio',
        ];

        $nomesRua = [
            fake('pt_BR')->lastName(),
            fake('pt_BR')->firstName() . ' ' . fake('pt_BR')->lastName(),
            fake('pt_BR')->randomElement(['São', 'Santa', 'Nossa Senhora']) . ' ' . fake('pt_BR')->firstName(),
            fake('pt_BR')->randomElement(['Dom', 'Dona']) . ' ' . fake('pt_BR')->firstName(),
        ];

        return fake('pt_BR')->randomElement($tiposLogradouro) . ' ' . fake('pt_BR')->randomElement($nomesRua);
    }

    /**
     * Gera um nome de bairro em português brasileiro.
     */
    private function gerarBairro(): string
    {
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

        return fake('pt_BR')->randomElement($prefixosBairro) . ' ' . fake('pt_BR')->randomElement($nomesBairro);
    }

    /**
     * Define o formato da barra de progresso.
     *
     * @return string
     */
    private function formatProgress(): string
    {
        return ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%';
    }

    /**
     * Define a quantidade de clientes a serem criados.
     *
     * @param int $quantidade
     * @return $this
     */
    public function quantidade(int $quantidade): self
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    /**
     * Define o tamanho do lote para criação em batches.
     *
     * @param int $tamanho
     * @return $this
     */
    public function tamanhoLote(int $tamanho): self
    {
        $this->tamanhoLote = $tamanho;
        return $this;
    }
}
