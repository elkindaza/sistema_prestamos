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
        Schema::create('caja', function (Blueprint $table) {
            $table->id();

            $table->dateTime('fecha');
            $table->decimal('monto', 14, 2);
            $table->string('direccion', 10); // IN / OUT
            $table->string('concepto', 150);

            $table->string('tipo_referencia', 50);
            $table->unsignedBigInteger('id_referencia');

            $table->unsignedBigInteger('creado_por');
            $table->unsignedBigInteger('doc_id')->nullable();

            $table->decimal('saldo_despues', 14, 2);
            $table->string('estado', 30)->default('normal');
            $table->text('nota')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('creado_por')->references('id')->on('users');
            $table->foreign('doc_id')->references('id')->on('documentos');

            $table->index('fecha');
            $table->index(['tipo_referencia', 'id_referencia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja');
    }
};
