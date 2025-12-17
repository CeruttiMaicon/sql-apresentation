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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id(); // bigInteger para suportar milhões de registros
            $table->unsignedSmallInteger('cidade_id'); // smallInteger porque referencia cidades.id
            $table->unsignedSmallInteger('estado_id'); // smallInteger porque referencia estados.id
            $table->string('rua');
            $table->string('cep');
            $table->string('numero');
            $table->string('bairro');
            $table->timestamps();

            // Foreign keys (já criam índices automaticamente, mas deixando explícito)
            $table->foreign('cidade_id')->references('id')->on('cidades')->onDelete('cascade');
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('cascade');

            // Índices adicionais para melhorar performance em consultas comuns
            $table->index('cidade_id', 'idx_enderecos_cidade_id');
            $table->index('estado_id', 'idx_enderecos_estado_id');
            $table->index('cep', 'idx_enderecos_cep'); // Útil para buscas por CEP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
