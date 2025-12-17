<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id(); // bigInteger para suportar milhões de registros
            $table->string('nome');
            $table->string('email');
            $table->string('sexo');
            $table->string('celular');
            $table->unsignedBigInteger('endereco_id')->nullable(); // bigInteger porque referencia enderecos.id
            $table->timestamps();

            // Foreign key (já cria índice automaticamente, mas deixando explícito)
            $table->foreign('endereco_id')->references('id')->on('enderecos')->onDelete('set null');

            // Índices para melhorar performance em consultas comuns
            $table->index('sexo', 'idx_clientes_sexo'); // Índice na coluna sexo
            $table->index('endereco_id', 'idx_clientes_endereco_id');
            $table->index('email', 'idx_clientes_email'); // Útil para buscas por email
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
