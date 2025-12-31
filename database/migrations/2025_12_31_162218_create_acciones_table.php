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
        Schema::create('acciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('prestamo_id');
            $table->dateTime('accion_en');
            $table->string('canal', 30);
            $table->string('resultado', 100);
            $table->dateTime('siguiente_accion_en')->nullable();
            $table->text('notas')->nullable();

            $table->unsignedBigInteger('creado_por');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('prestamo_id')->references('id')->on('prestamos');
            $table->foreign('creado_por')->references('id')->on('users');

            $table->index('prestamo_id');
            $table->index('accion_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acciones');
    }
};
