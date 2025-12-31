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
        Schema::create('contribuciones', function (Blueprint $table) {
           $table->id();

            $table->unsignedBigInteger('asociado_id');
            $table->decimal('monto', 14, 2);
            $table->dateTime('aportado_en');
            $table->string('metodo', 50);
            $table->string('referencia', 100)->nullable();
            $table->unsignedBigInteger('adjunto_id')->nullable();

            $table->timestamp('created_at')->useCurrent();

           
            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->foreign('adjunto_id')->references('id')->on('documentos');

           
            $table->index('asociado_id');
            $table->index('aportado_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribuciones');
    }
};
