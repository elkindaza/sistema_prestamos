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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cliente_id');
            $table->decimal('monto_principal', 14, 2);
            $table->unsignedInteger('meses_plazo');
            $table->decimal('tasa_interes', 6, 4);

            $table->string('tipo_interes', 30)->default('mensual');
            $table->string('tipo_cuota', 30)->default('fija');

            $table->date('fecha_inicio');
            $table->date('fecha_primera_cuota');
            $table->date('fecha_vencimiento');

            $table->string('frecuencia', 20)->default('mensual');
            $table->string('estado', 30)->default('en_analisis');

            $table->dateTime('aprobado_en')->nullable();
            $table->unsignedBigInteger('aprobado_por')->nullable();
            $table->text('nota_aprobacion')->nullable();

            $table->dateTime('desembolsado_en')->nullable();
            $table->unsignedBigInteger('desembolsado_por')->nullable();

            $table->unsignedBigInteger('documento_desembolso_id')->nullable();

            $table->text('nota')->nullable();

            $table->timestamps();

            // FKs
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('aprobado_por')->references('id')->on('users');
            $table->foreign('desembolsado_por')->references('id')->on('users');
            $table->foreign('documento_desembolso_id')->references('id')->on('documentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
