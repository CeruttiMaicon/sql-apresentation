<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
    private int $tamanhoLote = 100;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inicio = microtime(true);
        
        $totalValor = $this->quantidade > 0 ? $this->quantidade : 1;
        
        $progress = new ProgressBar(new ConsoleOutput(), $totalValor);
        $progress->setFormat($this->formatProgress());
        $progress->start();

        $criados = 0;
        $lotes = ceil($this->quantidade / $this->tamanhoLote);

        for ($lote = 0; $lote < $lotes; $lote++) {
            $restante = $this->quantidade - $criados;
            $tamanhoAtual = min($this->tamanhoLote, $restante);

            // Criar lote de clientes
            Cliente::factory($tamanhoAtual)->create();

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
