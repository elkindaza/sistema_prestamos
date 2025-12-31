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
            $table->id();
            $table->string('tipo_cliente', 20);
            $table->string('nombre_completo', 150);
            $table->string('tipo_documento', 20);
            $table->string('numero_documento', 30);
            $table->string('telefono', 30);
            $table->string('email', 150)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('nivel_riesgo', 20)->default('medio');
            $table->text('nota')->nullable();
            $table->string('status', 30)->default('activo');
            
            $table->timestamps();

            $table->unique(['tipo_documento', 'numero_documento'], 'uq_cliente_doc');
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
