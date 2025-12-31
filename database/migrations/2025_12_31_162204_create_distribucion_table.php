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
        Schema::create('distribucion', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('periodo_id');
            $table->unsignedBigInteger('asociado_id');

            $table->decimal('base_contribucion', 14, 2);
            $table->decimal('porcentaje_participacion', 6, 4);
            $table->decimal('importe_beneficios', 14, 2);

            $table->string('estado_pago', 30)->default('pendiente');
            $table->dateTime('pagado_en')->nullable();
            $table->unsignedBigInteger('documento_pago_id')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('periodo_id')->references('id')->on('periodo_beneficio');
            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->foreign('documento_pago_id')->references('id')->on('documentos');

            $table->index('periodo_id');
            $table->index('asociado_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribucion');
    }
};
