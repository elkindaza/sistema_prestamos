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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('prestamo_id');
            $table->unsignedInteger('numero');
            $table->date('fecha_vencimiento');
            $table->decimal('capital_programado', 14, 2);
            $table->decimal('interes_programado', 14, 2);
            $table->decimal('total_programado', 14, 2);
            $table->decimal('interes_pagado', 14, 2)->default(0.00);
            $table->decimal('mora_pagada', 14, 2)->default(0.00);
            $table->decimal('total_pagado', 14, 2)->default(0.00);
            $table->decimal('saldo_cuota', 14, 2);
            $table->string('estado', 30)->default('pendiente');
            $table->dateTime('pagado_en')->nullable();
            $table->timestamps();
         
            $table->foreign('prestamo_id')->references('id')->on('prestamos');

            $table->unique(['prestamo_id', 'numero'], 'uq_cuota_num');
            $table->index('prestamo_id');
            $table->index('fecha_vencimiento');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
