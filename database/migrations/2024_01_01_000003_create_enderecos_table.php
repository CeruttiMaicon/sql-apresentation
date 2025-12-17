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

            $table->foreign('cidade_id')->references('id')->on('cidades')->onDelete('cascade');
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('cascade');
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
