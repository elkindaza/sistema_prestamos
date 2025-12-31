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
        Schema::create('periodo_beneficio', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado', 30)->default('abierto');

            $table->decimal('ingresos_interes', 14, 2)->default(0.00);
            $table->decimal('ingresos_moro', 14, 2)->default(0.00);
            $table->decimal('gastos', 14, 2)->default(0.00);
            $table->decimal('beneficio_neto', 14, 2)->default(0.00);

            $table->dateTime('calculado_en')->nullable();
            $table->unsignedBigInteger('calculado_por')->nullable();

            $table->dateTime('cerrado_en')->nullable();
            $table->unsignedBigInteger('cerrado_por')->nullable();

            $table->text('nota')->nullable();
            $table->timestamps();

            $table->foreign('calculado_por')->references('id')->on('users');
            $table->foreign('cerrado_por')->references('id')->on('users');

            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodo_beneficio');
    }
};
