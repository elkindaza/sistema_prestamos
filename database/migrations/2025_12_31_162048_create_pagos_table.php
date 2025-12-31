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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('prestamo_id');
            $table->dateTime('pagado_en');
            $table->string('metodo', 50);
            $table->string('referencia', 100)->nullable();
            $table->unsignedBigInteger('doc_id')->nullable();
            $table->unsignedBigInteger('recibido_por');
            $table->text('notas')->nullable();
            $table->decimal('monto', 14, 2);

            $table->string('estado', 30)->default('registrado');
            $table->dateTime('anulado_en')->nullable();
            $table->unsignedBigInteger('anulado_por')->nullable();
            $table->text('motivo_anulacion')->nullable();
            $table->string('tipo', 30)->default('normal');

            $table->timestamps();

            $table->foreign('prestamo_id')->references('id')->on('prestamos');
            $table->foreign('doc_id')->references('id')->on('documentos');
            $table->foreign('recibido_por')->references('id')->on('users');
            $table->foreign('anulado_por')->references('id')->on('users');

            $table->index('prestamo_id');
            $table->index('pagado_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
